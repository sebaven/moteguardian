<?php
/**
 * @date 20/05/2008
 * @version 1.0
 * @author glerendegui
 * 
 * Clase template para implementar modulos recolectores
 * 
 */

include_once(BASE_DIR."clases/negocio/clase.Actividad.php");
include_once(BASE_DIR."clases/batch/clase.RecolectorBase.php");

class Recolector extends RecolectorBase {
	function Recolector() {
		
	}
	
	function preparar($actividad) {
		// Carga las configuraciones necesarios en la variable "datos"	
	}
	
	function recolectarActividad($actividadEjecutada,$archivos=0) {
		// No debe tener ninguna carga de la base de datos. Todas los valores debieron cargarse en preparar() 
	}
	
	final function registrarArchivo($ubicacionArchivoServidor,$nombreArchivoServidor,$tamanio) {
		  $this->_registrarArchivo($ubicacionArchivoServidor,$nombreArchivoServidor,$tamanio);
	}
	
	final function registrarArchivoOk($key,$nombreArchivoTemporal) {
		return $this->_registrarArchivoOk($key,$nombreArchivoTemporal);
	}
	
	final function registrarArchivoError($key,$errorDescripcion) {
		return $this->_registrarArchivoError($key,$errorDescripcion);
	}
	
	final function getCarpetaDestino() {
		return $this->_getCarpetaDestino();
	}

	/*
	 * Archivos: vector del tipo
	 * [n]['archivoOriginal']="asdasd";
	 * [n]['archivoTemporal']="";
	 * [n]['ubicacionOriginal']="asdasd";
	 * [n]['ubicacionTemporal']=;
	 * [n]['idTarea']=id_tarea;
	 * [n]['idEstadoTareaUltimo']=id_estado_tarea;
	 */ 
	final function getArchivosARecolectar() {
		return $this->_getArchivosARecolectar();
	}
	
}

?>