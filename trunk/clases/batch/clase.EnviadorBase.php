<?php
/**
 * @date 21/05/2008
 * @version 1.0
 * @author glerendegui
 * 
 * Esta clase contiene los métodos que invocan por dentro los enviador. Está la lógica del recoletor
 */

include_once(BASE_DIR."clases/dao/dao.DatosProceso.php");
include_once(BASE_DIR."clases/dao/dao.EstadoTarea.php");

class EnviadorBase {
		
	var $idActividadEnvio;

	var $datorProceso;
	/*
	 * Archivos: vector del tipo
	 * [n]['archivoOriginal']="asdasd";
	 * [n]['archivoTemporal']="asdasd";
	 * [n]['ubicacionOriginal']="asdasd";
	 * [n]['ubicacionTemporal']="asdasd";
 	 * [n]['idTarea']=id_tarea;
	 * 	 */ 		
	var $archivosAEnviar;
	var $archivosOk = array();
	var $archivosError = array();
	var $carpetaDestino = "";
	
	function EnviadorBase() {
		
	}
	
	final function cargarDatosProceso($idTarea,$archivoOriginal) {
		if(!$this->datosProceso) {
			$datosProcesoDAO = new DatosProcesoDAO();
			$tmp = $datosProcesoDAO->filterByField("id_proceso_actividad_envio",$this->idActividadEnvio);
			if(sizeof($tmp)==0) {
				registrarLog(BATCH_LOG_ALERT,"Problema enviando la tarea ".$idTarea."(".$archivoOriginal.") - no se encontro datos proceso");
				return false;
			}
			$this->datosProceso = $tmp[0];
		}
		return true;			
	}
	
	
	final function _registrarEstadoTarea($key,$fallido,$nombreArchivoTemporal) {
		if(empty($this->datosProceso)) 
			if(!($this->cargarDatosProceso($this->archivosAFiltrar[$key]['idTarea'],$this->archivosAFiltrar[$key]['archivoOriginal'])))
				return false;

		$estadoTareaDAO = new EstadoTareaDAO();
		$estadoTareaDAO->marcarTareaSinUltimo($this->archivosAEnviar[$key]['idTarea']);

		$estadoTarea = new EstadoTarea();
		
		$fecha = new Fecha();
		$fecha->loadFromNow();
		$estadoTarea->id_tarea = $this->archivosAEnviar[$key]['idTarea'];
		$estadoTarea->id_datos_proceso = $this->datosProceso->id;
		$estadoTarea->nombre_original = $this->archivosAEnviar[$key]['archivoTemporal'];
		$estadoTarea->nombre_actual = $nombreArchivoTemporal;
		$estadoTarea->ubicacion = $this->_getCarpetaDestino();
		$estadoTarea->baja_logica = FALSE_;
		$estadoTarea->timestamp_ingreso = $fecha->toString(); 
		$estadoTarea->ultimo = TRUE_;
		$estadoTarea->fallo = $fallido;
		// Esto es siempre falso porque solo se marca asi si trata de un filtro, asi se sabe donde buscar a la hora de enviar de nuevo
		$estadoTarea->esperando_envio = FALSE_;
		$estadoTarea->pid = posix_getpid();
		$estadoTarea->save();
		
		return $estadoTarea->id;
	}
	
	// Para el enviador si hay que conservar la key en los vectores de archivos, para filtro no
	
	final function _registrarArchivoOk($key,$nombreFinal) {
		registrarLog(BATCH_LOG_DEBUG,"Se proceso ok el archivo ".$this->archivosAEnviar[$key]['archivoTemporal']." y se envio como ".$nombreFinal);
		
		if(!$this->_registrarEstadoTarea($key,FALSE_,$nombreFinal)) {
			// No se pudo registrar el envio
			return $this->_registrarArchivoError($key,$nombreFinal,"No se pudo generar el seguimiento de la tarea");
		}
		
		$this->archivosOk[$key] = array('archivoOriginal'=>$this->archivosAEnviar[$key]['archivoTemporal'],
									'archivoTemporal'=>$nombreFinal,
									'ubicacionOriginal'=>$this->archivosAEnviar[$key]['ubicacionTemporal'],
									'ubicacionTemporal'=>$this->_getCarpetaDestino(),
									'idTarea'=>$this->archivosAEnviar[$key]['idTarea']);
		
		return true;		
	}
	
	final function _registrarArchivoError($key,$nombreFinal,$errorDescripcion) {
		registrarLog(BATCH_LOG_DEBUG,"Se envio mal el archivo ".$this->archivosAEnviar[$key]['archivoTemporal']);
		registrarLog(BATCH_LOG_ALERT,"Se envio mal el archivo ".$this->archivosAEnviar[$key]['archivoTemporal']." - ".$errorDescripcion);
		
		$this->_registrarEstadoTarea($key,TRUE_,$nombreFinal);
		
		$this->archivosError[$key] = array('archivoOriginal'=>$this->archivosAEnviar[$key]['archivoTemporal'],
									'archivoTemporal'=>$nombreFinal,
									'ubicacionOriginal'=>$this->archivosAEnviar[$key]['ubicacionTemporal'],
									'ubicacionTemporal'=>$this->_getCarpetaDestino(),
									'idTarea'=>$this->archivosAEnviar[$key]['idTarea']);
		
		return true;
		
	}
	
	final function _getArchivosAEnviar() {
		return $this->archivosAEnviar;
	}
	
	final function _setArchivosAEnviar($archivos) {
		$this->archivosAEnviar = $archivos;
	}
	
	final function _setCarpetaDestino($carpeta) {
		$this->carpetaDestino = $carpeta;
	}
	
	final function _getCarpetaDestino() {
		return $this->carpetaDestino;
	}
	
}
?>