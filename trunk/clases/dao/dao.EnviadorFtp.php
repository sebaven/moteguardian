<?php
include_once BASE_DIR."clases/negocio/clase.EnviadorFtp.php";

class EnviadorFTPDAO extends AbstractDAO
{
	function getEntity()
	{
		return new EnviadorFtp();
	}

	/**
	 * Devuelve el objeto de la clase EnviadorFtp cuyo id de host es el especificado
	 * 
	 * @access public
	 * @param $idHost (integer) es el id del host al cual está asociado
	 * @return (object) objeto de la clase EnviadorFtp cuyo id de host es el especificado. Vacío si no existe
	 */ 
	function getByIdHost($idHost = "") {	  
		
		// Se hace el escapeo de caracteres en las variables recibidas por parametro
		$idHost = addslashes($idHost);
		
		$array = $this->filterByField("id_host",$idHost);
		if(sizeof($array) >= 1) {			
			return $array[0];		
		} else {			
			return null;
		}
	}
}
?>