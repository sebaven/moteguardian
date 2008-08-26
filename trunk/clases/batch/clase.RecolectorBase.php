<?php
/**
 * @date 20/05/2008
 * @version 1.0
 * @author glerendegui
 * 
 * Esta clase contiene los métodos que invocan por dentro los recolectores. Está la lógica del recoletor
 */

include_once(BASE_DIR."clases/dao/dao.DatosProceso.php");
include_once(BASE_DIR."clases/negocio/clase.EstadoTarea.php");
include_once(BASE_DIR."clases/negocio/clase.Tarea.php");
include_once(BASE_DIR."clases/negocio/clase.Actividad.php");
include_once(BASE_DIR."clases/dao/dao.Tarea.php");
include_once(BASE_DIR."clases/dao/dao.EstadoTarea.php");


class RecolectorBase {
	/*
	 * Archivos: vector del tipo
	 * [n]['archivoOriginal']="asdasd";
	 * [n]['archivoTemporal']="asdasd";
	 * [n]['ubicacionOriginal']="asdasd";
	 * [n]['ubicacionTemporal']="asdasd";
	 * [n]['idTarea']=id_tarea;
	 * [n]['tamanio']=23123123213;
	 */ 
	var $archivosARecolectar = array();
	var $archivosOk = array();
	var $archivosError = array();
	var $actividadEjecutada;
	var $datosProceso = 0;
	var $carpetaDestino = "";
	
	function RecolectorBase() {
		
	}
	
	final function _getCarpetaDestino() {
		return $this->carpetaDestino;
	}
	
	final function _setCarpetaDestino($carpeta) {
		global $_config;
		$this->carpetaDestino = $carpeta;
		// Verifico si existe la carpeta para archivos de destino
		if(!file_exists($carpeta)) {
			if(!mkdir($carpeta)) {
				if(!file_exists($carpeta)) {
					registrarLog(BATCH_LOG_ERROR,"Carpeta no encontrada ".$carpeta." - Tampoco se pudo crear");
					return false;
				}
			}
		}
		return true;
	}
	
	final function _getListaArchivosOk() {
		return $this->archivosOk;
	}
	
	final function cargarDatosProceso() {
		$id_actividad = $this->actividadEjecutada->id_actividad;
		$actividad = new Actividad($id_actividad);
		if(empty($actividad->id_central)) {
			registrarLog(BATCH_LOG_ERROR,"No se encontró datos proceso asociado a la actividad ".$id_actividad);
			return false;
		}
		else {
			$datosProcesoDAO = new DatosProcesoDAO();
			$this->datosProceso = $datosProcesoDAO->getDatosProcesoRecolectado($actividad->id_central);
		}
		return true;		 
	}
	
	final function _registrarArchivo($ubicacionArchivoServidor,$nombreArchivoServidor,$tamanio) {
		registrarLog(BATCH_LOG_DEBUG,"Se encontró el archivo ".$nombreArchivoServidor);

		if(!$this->datosProceso) {
			$this->cargarDatosProceso();
		}
		
		$tareaDAO = new TareaDAO();
		if($tareaDAO->archivoYaRecolectado($this->actividadEjecutada->id_actividad,$ubicacionArchivoServidor,$nombreArchivoServidor)) {
			// Ya se recolectó en otra ocasión, paso de largo
			return true;
		}
		
		$tarea = new Tarea();
		$tarea->id_actividad_ejecutada = $this->actividadEjecutada->id;
		$tarea->baja_logica = FALSE_;
		$tarea->id_actividad = $this->actividadEjecutada->id_actividad;
		$tarea->nombre_original = $nombreArchivoServidor;
		$tarea->ubicacion_original = $ubicacionArchivoServidor;
		$tarea->esperando_envio = FALSE_;
		$tarea->pendiente_recoleccion = TRUE_;
		$tarea->tamanio = $tamanio;
		$tarea->save();
		
		$this->archivosARecolectar[]=array('archivoOriginal'=>$nombreArchivoServidor,
								'archivoTemporal'=>"",
								'ubicacionOriginal'=>$ubicacionArchivoServidor,
								'ubicacionTemporal'=>$this->_getCarpetaDestino(),
								'idTarea'=>$tarea->id,
								'tamanio'=>$tamanio);
		
		return true;
	}
	
	final function _registrarEstadoTarea($key,$fallido,$nombreArchivoTemporal) {
		if(empty($this->datosProceso)) 
			if (!($this->cargarDatosProceso())) {
				return false;
			}
		
		$estadoTareaDAO = new EstadoTareaDAO();
		$estadoTareaDAO->marcarTareaSinUltimo($this->archivosARecolectar[$key]['idTarea']);
		
		$estadoTarea = new EstadoTarea();
		$fecha = new Fecha();
		$fecha->loadFromNow();
		$estadoTarea->id_tarea = $this->archivosARecolectar[$key]['idTarea'];
		$estadoTarea->id_datos_proceso = $this->datosProceso->id;
		$estadoTarea->nombre_original = $this->archivosARecolectar[$key]['archivoOriginal'];
		$estadoTarea->nombre_actual = $nombreArchivoTemporal;
		$estadoTarea->ubicacion = $this->_getCarpetaDestino();
		$estadoTarea->baja_logica = FALSE_;
		$estadoTarea->timestamp_ingreso = $fecha->toString(); 
		$estadoTarea->ultimo = TRUE_;
		$estadoTarea->fallo = $fallido;
		$estadoTarea->esperando_envio = FALSE_;
		$estadoTarea->pid = posix_getpid();
		$estadoTarea->save();
		return $estadoTarea->id;
	}
	
	
	final function _registrarArchivoOk($key,$nombreArchivoTemporal) {
		registrarLog(BATCH_LOG_DEBUG,"Se recolecto ok el archivo ".$this->archivosARecolectar[$key]['archivoOriginal']." y se descargo como ".$nombreArchivoTemporal);
		
		if(!$this->_registrarEstadoTarea($key,FALSE_,$nombreArchivoTemporal)) {
			return $this->_registrarArchivoError($key,"No se puedo crear el seguimiento de la tarea");
		}
		
		$tarea = new Tarea($this->archivosARecolectar[$key]['idTarea']);
		$tarea->pendiente_recoleccion = FALSE_;
		$tarea->save();
		
		
		$this->archivosOk[] = array('archivoOriginal'=>$this->archivosARecolectar[$key]['archivoOriginal'],
									'archivoTemporal'=>$nombreArchivoTemporal,
									'ubicacionOriginal'=>$this->archivosARecolectar[$key]['ubicacionOriginal'],
									'ubicacionTemporal'=>$this->_getCarpetaDestino(),
									'idTarea'=>$this->archivosARecolectar[$key]['idTarea']);

		$tarea = new Tarea($this->archivosARecolectar[$key]['idTarea']);
		$tarea->pendiente_recoleccion = FALSE_;
		
		return true;
	}
	
	final function _registrarArchivoError($key,$errorDescripcion) {
		registrarLog(BATCH_LOG_DEBUG,"Se recolecto mal el archivo ".$this->archivosARecolectar[$key]['archivoOriginal']);
		registrarLog(BATCH_LOG_ALERT,"Se recolecto mal el archivo ".$this->archivosARecolectar[$key]['archivoOriginal']." - ".$errorDescripcion);
		
		$this->_registrarEstadoTarea($key,TRUE_,'');
		
		$this->archivosError[] = array('archivoOriginal'=>$this->archivosARecolectar[$key]['archivoOriginal'],
									'archivoTemporal'=>'',
									'ubicacionOriginal'=>$this->archivosARecolectar[$key]['ubicacionOriginal'],
									'ubicacionTemporal'=>'',
									'idTarea'=>$this->archivosARecolectar[$key]['idTarea']);
		
		return true;
	}
	
		
	final function _getArchivosARecolectar() {
		return $this->archivosARecolectar;
	}
	
	
}
?>