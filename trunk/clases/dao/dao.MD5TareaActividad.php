<?php
include_once BASE_DIR."clases/negocio/clase.MD5TareaActividad.php";

class MD5TareaActividadDAO extends AbstractDAO
{
	function getEntity()
	{
		return new MD5TareaActividad();
	}
	
	function existe($md5, $idActividad) {
		$entity = $this->getEntity();
		
		$sql = "SELECT ";
		$sql .= 	" id ";
		$sql .= "FROM ".$entity->_tablename." ";
		$sql .= "WHERE id_actividad = '".$idActividad."' ";
		$sql .= 	"AND md5 = '".$md5."' ";
		
		$res = $entity->_db->leer($sql);
		
		if($entity->_db->numero_filas($res)) return TRUE_;
		else return FALSE_;
	}
}
?>