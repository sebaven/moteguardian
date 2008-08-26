<?php
// Batch
include_once(BASE_DIR."clases/modulos/clase.Filtro.php");
include_once(BASE_DIR."clases/dao/dao.MD5TareaActividad.php");

class ModuloFiltroValidador extends Filtro {

	function ModuloFiltroValidador() {

	}

	function preparar($actividad) {
	}

	function copiarParcheandoWinUnix($archivo_original,$archivo_nuevo) {
		$arch_orig = fopen($archivo_original,"r");
		$arch_dest = fopen($archivo_nuevo,"w");

		$buffer = 0;
		while (!feof($arch_orig)) {
			$buffer = fgets($arch_orig, 4096);
			// Terrible harcodeo
			if(!(strstr($archivo_original,"bil")))
				$buffer = str_replace("\r\n","\n",$buffer);
			fwrite($arch_dest,$buffer);
		}


		fclose($arch_orig);
		fclose($arch_dest);

		return true;
	}

	function filtrarArchivos($actividadEnvio) {
		// Obtengo los archivos a filtrar
		$archivos = $this->_getArchivosAFiltrar();
		foreach($archivos as $key=>$archivo) {
			$nuevoNombre = $archivo['archivoTemporal'];

			//Hacer MD5 del archivo y buscar en alguna tabla
			$nombreArchivoACalularMD5 = $archivo['ubicacionTemporal']."/".$archivo['archivoTemporal'];
			$md5 = $this->filemd5($nombreArchivoACalularMD5);
				
			if( ($md5) &&
			(!$this->existe($md5,$this->actividadEjecutada->id_actividad)) &&
			($this->copiarParcheandoWinUnix($archivo['ubicacionTemporal']."/".$archivo['archivoTemporal'],$this->getCarpetaDestino()."/".$nuevoNombre))) {
				$this->agregarMD5($md5,$this->actividadEjecutada->id_actividad);
				$this->registrarArchivoOk($key,$nuevoNombre);
			}
			else {
				$this->registrarArchivoError($key,$nuevoNombre,"Existe MD5");
			}
		}
	}

	/*
	 * Archivos: vector del tipo
	 * [n]['archivoOriginal']="asdasd";
	 * [n]['archivoTemporal']="asdasd";
	 * [n]['idTarea']=id_tarea;
	 */
	final function getListaArchivos() {
		return $this->_getListaArchivos();
	}

	function fileMD5($nombreArchivo){
		return md5_file($nombreArchivo);
	}

	function existe($md5, $idActividad) {
		$MD5TareaActividadDAO = new MD5TareaActividadDAO();
		return $MD5TareaActividadDAO->existe($md5,$idActividad);
	}

	function agregarMD5($md5, $idActividad) {
		$md5TareaActividad = new MD5TareaActividad();
		$md5TareaActividad->md5 = $md5;
		$md5TareaActividad->id_actividad = $idActividad;
		$md5TareaActividad->save();
	}


}


?>