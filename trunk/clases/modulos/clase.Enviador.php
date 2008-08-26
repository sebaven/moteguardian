<?php
/**
 * @date 21/05/2008
 * @version 1.0
 * @author glerendegui
 * 
 * Clase template para implementar modulos recolectores
 * 
 */

include_once(BASE_DIR."clases/negocio/clase.Actividad.php");
include_once(BASE_DIR."clases/batch/clase.EnviadorBase.php");

class Enviador extends EnviadorBase {
	function Enviador() {
		
	}
	
	function preparar($actividadEnvio) {
	
	}
	
	function enviarArchivos() {
	}

	/*
	 * Archivos: vector del tipo
	 * [n]['archivoOriginal']="asdasd";
	 * [n]['archivoTemporal']="asdasd";
	 * [n]['idTarea']=id_tarea;
	 */ 
	final function getArchivosAEnviar() {
		return $this->_getArchivosAEnviar();
	}	
	
	final function registrarArchivoOk($key,$nombreFinal) {
		return $this->_registrarArchivoOk($key,$nombreFinal);
	}
	
	final function registrarArchivoError($key,$nombreFinal,$errorDescripcion) {
		return $this->_registrarArchivoError($key,$nombreFinal,$errorDescripcion);
	}
	
	final function getCarpetaDestino() {
		return $this->_getCarpetaDestino();
	}	
	
}

?>