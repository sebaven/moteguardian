<?php
include_once BASE_DIR."clases/negocio/clase.MD5TareaRecoleccion.php";

class MD5TareaRecoleccionDAO extends AbstractDAO
{
	function getEntity()
	{
		return new MD5TareaRecoleccion();
	}
	
	function existe($md5, $idRecoleccion) {
		$entity = $this->getEntity();
		
		$sql = "SELECT * ";
		$sql .= "FROM ".$entity->_tablename." ";
		$sql .= "WHERE id_recoleccion = '".$idRecoleccion."' ";
		$sql .= "AND md5 = '".$md5."' ";
		
		$res = $entity->_db->leer($sql);
		
		return $this->_rs2Entity($res);
		
	}
}
?>