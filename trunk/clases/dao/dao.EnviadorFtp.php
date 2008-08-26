<?php
include_once BASE_DIR."clases/negocio/clase.EnviadorFtp.php";

class EnviadorFTPDAO extends AbstractDAO
{
	function getEntity()
	{
		return new EnviadorFtp();
	}

	/**
	 * Devuelve el objeto de la clase EnviadorFtp cuyo id de actividad_envio es el especificado
	 * 
	 * @access public
	 * @param $idActividadEnvio (integer) es el id de la actividad_envio a la cual est� asociado
	 * @return (object) objeto de la clase EnviadorFtp cuyo id de actividad_envio es el especificado. Vac�o si no existe
	 */ 
	function getByIdActividadEnvio($idActividadEnvio = "") {	  
		
		// Se hace el escapeo de caracteres en las variables recibidas por parametro
		$idActividadEnvio = addslashes($idActividadEnvio);
		
		$array = $this->filterByField("id_actividad_envio",$idActividadEnvio);
		if(sizeof($array>1)) {
			return $array[0];		
		} else {
			return null;
		}
	}
}
?>