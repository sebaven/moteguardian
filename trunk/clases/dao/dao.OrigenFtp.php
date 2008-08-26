<?php
include_once BASE_DIR."clases/negocio/clase.OrigenFtp.php";

class OrigenFtpDAO extends AbstractDAO
{
	function getEntity()
	{
		return new OrigenFtp(NULL,NULL);
	}

	/**
	 * Devuelve el objeto de la clase OrigenFtp cuyo id de recolector FTP es el especificado
	 * 
	 * @access public
	 * @param $idRecolector (integer) es el id del recolector a la cual está asociado
	 * @return (object) objeto de la clase OrigenFtp cuyo id de recolector FTP es el especificado. Vacío si no existe
	 */ 
	function getByIdRecolectorFtp($idRecolector = "")
	{	  
		// Se hace el escapeo de caracteres en las variables recibidas por parametro
		$idRecolector = addslashes($idRecolector);

		$sql =  "id_recolector_ftp ='".$idRecolector."' ";
		$sql .= "AND baja_logica = 0 ";
		$sql .= "ORDER BY nro_orden ASC ";

		$array = $this->filterBy($sql);

		return $array;
	}
}
?>