<?php
include_once BASE_DIR."clases/batch/entidades/clase.ProcesoEjecutandose.php";

class ProcesoEjecutandoseDAO extends AbstractDAO
{
	function getEntity()
	{
		return new ProcesoEjecutandose();
	}
	
	function borrarTareasEjecutandose() {
		$entity = $this->getEntity();
		
		$sql = "DELETE FROM proceso_ejecutandose";
		$res = $entity->_db->ejecutar($sql);
		return ($res==true);
	}
	
	function getByIdActividad($idActividad,$tipo) {
		$entity = $this->getEntity();
		
		$sql = "SELECT * FROM ".$entity->_tablename." WHERE id_actividad = '".$idActividad."' AND tipo = '".$tipo."'";
		
		$res = $entity->_db->leer($sql);
		
		return $this->_rs2Entity($res);
	}
	
}
?>