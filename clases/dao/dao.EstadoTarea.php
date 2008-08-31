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
	
	function findByIdInformacionEstadoAndNombreOriginal($idInformacionEstado, $nombre_original) {
		$entity = $this->getEntity();
		
		$sql = "SELECT * FROM ".$entity->_tablename;
		$sql.= " WHERE id_informacion_estado = '".$idInformacionEstado."' AND nombre_original = '".$nombre_original."'";
		
		$res = $entity->_db->leer($sql);
		if(!isset($res)) {
			return array();
		} else return $this->_rs2Collection($res);
	}
	
	
}
?>