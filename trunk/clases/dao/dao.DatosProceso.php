<?php
include_once BASE_DIR."clases/negocio/clase.DatosProceso.php";

class DatosProcesoDAO extends AbstractDAO
{
	function getEntity()
	{
		return new DatosProceso();
	}
	
	function getDatosProcesoRecolectado($idCentral) {
		$entity = $this->getEntity();
		$sql = "SELECT * FROM ".$entity->_tablename." WHERE id_proceso_central = '".$idCentral."' ";
		
		// *** Realizar la consutla *** //
		$res = $entity->_db->leer($sql);
		
		return $this->_rs2Entity($res);
	}
	
}
?>