<?php
/**
 * @date 28/05/2008
 * @version 1.0
 * @author glerendegui
 * 
 * Clase template para implementar modulos filtro
 * 
 */

include_once(BASE_DIR."clases/negocio/clase.Actividad.php");
include_once(BASE_DIR."clases/batch/clase.FiltroBase.php");

class Filtro extends FiltroBase {
	function Filtro() {
		
	}
	
	function preparar($actividad) {
		// Carga las configuraciones necesarios en la variable "datos"	
	}
	
	function filtrarArchivos() {
	}
	
	/*
	 * Archivos: vector del tipo
	 * [n]['archivoOriginal']="asdasd";
	 * [n]['archivoTemporal']="asdasd";
	 * [n]['idTarea']=id_tarea;
	 */ 
	final function getArchivosAFiltrar() {
		return $this->_getArchivosAFiltrar();
	}
	
	final function registrarArchivoOk($key,$nombreArchivoTemporal) {
		return $this->_registrarArchivoOk($key,$nombreArchivoTemporal);
	}
	
	final function registrarArchivoError($key,$nombreArchivoTemporal,$errorDescripcion) {
		return $this->_registrarArchivoError($key,$nombreArchivoTemporal,$errorDescripcion);
	}
	
	final function getCarpetaDestino() {
		return $this->_getCarpetaDestino();
	}
}

?>