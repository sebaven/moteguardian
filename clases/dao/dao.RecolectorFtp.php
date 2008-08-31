<?php
include_once BASE_DIR."clases/negocio/clase.RecolectorFtp.php";

class RecolectorFtpDAO extends AbstractDAO
{
	function getEntity()
	{
		return new RecolectorFtp();
	}

	/**
	 * Devuelve el objeto de la clase RecolectorFtp cuyo id de central es el especificado
	 * 
	 * @access public
	 * @param $idCentral (integer) es el id de la central a la cual está asociado
	 * @return (object) objeto de la clase RecolectorFtp cuyo id de central es el especificado. Vacío si no existe
	 */ 
	function getByIdCentral($idCentral = "") {
			  
		// Se hace el escapeo de caracteres en las variables recibidas por parametro
		$idCentral = addslashes($idCentral);
		
		$array = $this->filterByField("id_central",$idCentral);
		return $array[0];
	}
}
?>