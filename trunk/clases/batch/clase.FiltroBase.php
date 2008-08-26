<?php
/**
 * @date 28/05/2008
 * @version 1.0
 * @author glerendegui
 * 
 * Esta clase contiene los métodos que invocan por dentro los filtro. Está la lógica del filtro
 */

include_once(BASE_DIR."clases/negocio/clase.ActividadEjecutada.php");
include_once(BASE_DIR."clases/negocio/clase.EstadoTarea.php");
include_once(BASE_DIR."clases/negocio/clase.Tarea.php");
include_once(BASE_DIR."clases/dao/dao.EstadoTarea.php");


class FiltroBase {
	
	var $carpetaDestino = "";
		
	var $actividadEjecutada;
	
	var $idNodoPlantilla;
	
	var $datosProceso;
	
	var $ultimoDeLaPlantilla = FALSE_;
	
	/*
	 * Archivos: vector del tipo
	 * [n]['archivoOriginal']="asdasd";
	 * [n]['archivoTemporal']="asdasd";
	 * [n]['ubicacionOriginal']="asdasd";
	 * [n]['ubicacionTemporal']="asdasd";
 	 * [n]['idTarea']=id_tarea;
	 * 	 */ 		
	var $archivosAFiltrar;
	var $archivosOk = array();
	var $archivosError = array();
	
	function FiltroBase() {
		
	}
	
	final function _setCarpetaDestino($carpeta) {
		$this->carpetaDestino = $carpeta;
		// Verifico si existe la carpeta para archivos de destino
		if(!file_exists($carpeta)) {
			if(!mkdir($carpeta)) {
				registrarLog(BATCH_LOG_ERROR,"Carpeta no encontrada ".$carpeta." - Tampoco se pudo crear");
				return false;
			}
		}
		return true;
	}
	
	final function _getCarpetaDestino() {
		return $this->carpetaDestino;
	}
	
	final function _getArchivosAFiltrar() {
		return $this->archivosAFiltrar;
	}
	
	final function _getArchivosOk() {
		return $this->archivosOk;
	}
	
	final function _setArchivosAFiltrar($archivos) {
		$this->archivosAFiltrar = $archivos;
	}
	
	final function _getArchivosFiltrados() {
		return $this->archivosOk;
	}
	
	final function cargarDatosProceso($idTarea,$archivoOriginal) {
		if(!$this->datosProceso) {
			$datosProcesoDAO = new DatosProcesoDAO();
			$tmp = $datosProcesoDAO->filterByField("id_proceso_nodo_plantilla",$this->idNodoPlantilla);
			if(sizeof($tmp)==0) {
				registrarLog(BATCH_LOG_ALERT,"Problema filtrando la tarea ".$idTarea."(".$archivoOriginal.") en el nodo plantilla id ".$this->idNodoPlantilla." - no se encontro datos proceso");
				return false;
			}
			$this->datosProceso = $tmp[0];			
		}
		return true;
	}
	
	final function _registrarEstadoTarea($key,$fallido,$nombreArchivoTemporal) {
		if(empty($this->datosProceso))
			if(!($this->cargarDatosProceso($this->archivosAFiltrar[$key]['idTarea'],$this->archivosAFiltrar[$key]['archivoOriginal']))) {
				return false;
			}

		$estadoTareaDAO = new EstadoTareaDAO();
		$estadoTareaDAO->marcarTareaSinUltimo($this->archivosAFiltrar[$key]['idTarea']);
				
		$estadoTarea = new EstadoTarea();
		$fecha = new Fecha();
		$fecha->loadFromNow();
		$estadoTarea->id_tarea = $this->archivosAFiltrar[$key]['idTarea'];
		$estadoTarea->id_datos_proceso = $this->datosProceso->id;
		$estadoTarea->nombre_original = $this->archivosAFiltrar[$key]['archivoTemporal'];
		$estadoTarea->nombre_actual = $nombreArchivoTemporal;
		$estadoTarea->ubicacion = $this->_getCarpetaDestino();
		$estadoTarea->baja_logica = FALSE_;
		$estadoTarea->timestamp_ingreso = $fecha->toString(); 
		$estadoTarea->ultimo = TRUE_;
		$estadoTarea->fallo = $fallido;
		$estadoTarea->esperando_envio = ((!$fallido)&&($this->ultimoDeLaPlantilla==TRUE_))?TRUE_:FALSE_;
		$estadoTarea->pid = posix_getpid();
		$estadoTarea->save();
		return $estadoTarea->id;
	}
	
	// Para el enviador si hay que conservar la key en los vectores de archivos, para filtro no
	
	final function _registrarArchivoOk($key,$nombreArchivoTemporal) {
		registrarLog(BATCH_LOG_DEBUG,"Se proceso ok el archivo ".$this->archivosAFiltrar[$key]['archivoTemporal']." y se descargo como ".$nombreArchivoTemporal);
		
		if(!($this->_registrarEstadoTarea($key,FALSE_,$nombreArchivoTemporal))) {
			return $this->_registrarArchivoError($key,$nombreArchivoTemporal,"No se puedo crear seguimiento de la tarea");
		}
		
		$this->archivosOk[] = array('archivoOriginal'=>$this->archivosAFiltrar[$key]['archivoTemporal'],
									'archivoTemporal'=>$nombreArchivoTemporal,
									'ubicacionOriginal'=>$this->archivosAFiltrar[$key]['ubicacionTemporal'],
									'ubicacionTemporal'=>$this->_getCarpetaDestino(),
									'idTarea'=>$this->archivosAFiltrar[$key]['idTarea']);
		
		if($this->ultimoDeLaPlantilla) {
			// Marco tarea para enviar
			$tarea = new Tarea($this->archivosAFiltrar[$key]['idTarea']);
			$tarea->esperando_envio = TRUE_;
			$tarea->save();
		}
		
		return true;
	}
	
	final function _registrarArchivoError($key,$nombreArchivoTemporal,$errorDescripcion) {
		registrarLog(BATCH_LOG_DEBUG,"Se proceso mal el archivo ".$this->archivosAFiltrar[$key]['archivoTemporal']);
		registrarLog(BATCH_LOG_ALERT,"Se proceso mal el archivo ".$this->archivosAFiltrar[$key]['archivoTemporal']." - ".$errorDescripcion);
		$this->_registrarEstadoTarea($key,1,$nombreArchivoTemporal);
		
		$this->archivosError[] = array('archivoOriginal'=>$this->archivosAFiltrar[$key]['archivoTemporal'],
							'archivoTemporal'=>$nombreArchivoTemporal,
							'ubicacionOriginal'=>$this->archivosAFiltrar[$key]['ubicacionTemporal'],
							'ubicacionTemporal'=>$this->_getCarpetaDestino(),
							'idTarea'=>$this->archivosAFiltrar[$key]['idTarea']);


		return true;
	}
	
}
?>