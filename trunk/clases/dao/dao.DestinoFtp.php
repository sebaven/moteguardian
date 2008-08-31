<?php
include_once BASE_DIR."clases/negocio/clase.DestinoFtp.php";

class DestinoFtpDAO extends AbstractDAO
{
	function getEntity()
	{
		return new DestinoFtp(NULL,NULL);
	}

	/**
	 * Devuelve el objeto de la clase OrigenFtp cuyo id de enviador FTP es el especificado
	 * 
	 * @access public
	 * @param $idEnviador (integer) es el id del enviador a la cual está asociado
	 * @return (object) objeto de la clase DestinoFtp cuyo id de enviador FTP es el especificado. Vacío si no existe
	 */ 
	function getByIdEnviadorFtp($idEnviador = "")
	{	  
		// Se hace el escapeo de caracteres en las variables recibidas por parametro
		$idRecolector = addslashes($idEnviador);

		$sql =  "id_enviador_ftp ='".$idEnviador."' ";
		$sql .= "AND baja_logica = 0 ";
		$sql .= "ORDER BY nro_orden ASC ";

		$array = $this->filterBy($sql);

		return $array;
	}
}
?>