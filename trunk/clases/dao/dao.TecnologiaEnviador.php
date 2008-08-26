<?php
include_once BASE_DIR ."clases/negocio/clase.TecnologiaEnviador.php";

class TecnologiaEnviadorDAO extends AbstractDAO
{
	function getEntity()
	{
		return new TecnologiaEnviador();
	}
	
	/**
	 * Devuelve el objeto de la clase TecnologiaEnviador cuyo nombre es el especificado
	 * 
	 * @access public
	 * @param $nombre (string) es el nombre de la tecnologia
	 * @return (object) objeto de la clase TecnologiaEnviador cuyo nombre es el especificado. Vac�o si no existe
	 */ 
	function getByNombre($nombre = "")
	{	  
		$array = $this->filterByField("nombre_tecnologia",$nombre);
		return $array[0];
	}
}

?>