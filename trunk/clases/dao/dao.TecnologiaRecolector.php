<?php
include_once BASE_DIR."clases/negocio/clase.TecnologiaRecolector.php";

class TecnologiaRecolectorDAO extends AbstractDAO
{
	function getEntity()
	{
		return new TecnologiaRecolector();
	}
	
	/**
	 * Devuelve el objeto de la clase TecnologiaRecolector cuyo nombre es el especificado
	 * 
	 * @access public
	 * @param $nombre (string) es el nombre de la tecnologia
	 * @return (object) objeto de la clase TecnologiaRecolector cuyo nombre es el especificado. Vac�o si no existe
	 */ 
	function getByNombre($nombre = "")
	{	  
		$array = $this->filterByField("nombre_tecnologia",$nombre);//TODO: idem al anterior
		return $array[0];
	}

	function getByIdCentral($idCentral) {
		$entity = $this->getEntity();
		
		$sql = "SELECT t.* FROM tecnologia_recolector t ";
		$sql.= "JOIN central c ON c.id_tecnologia_recolector = t.id ";
		$sql.= "WHERE c.id = '".addslashes($idCentral)."'";
		
		// *** Realizar la consutla *** //
		$res = $entity->_db->leer($sql);
		
		return $this->_rs2Entity($res);		
	}
}

?>