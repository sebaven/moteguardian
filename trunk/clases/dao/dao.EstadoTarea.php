<?php
include_once BASE_DIR."clases/negocio/clase.EstadoTarea.php";

/**
 * @author cgalli
 */
class EstadoTareaDAO extends AbstractDAO
{
	function getEntity()
	{
		return new EstadoTarea();
	}
	
	function marcarTareaSinUltimo($id_tarea) {
		$entity = $this->getEntity();
		$sql = "UPDATE ".$entity->_tablename." SET ultimo = '".FALSE_."' WHERE id_tarea = '".$id_tarea."' AND ultimo = '".TRUE_."' ";
		$res = $entity->_db->ejecutar($sql);
		return true;
	}
	
}
?>