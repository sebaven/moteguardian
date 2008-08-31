<?php
include_once BASE_DIR."clases/negocio/clase.InformacionEstado.php";

/**
 * @author aamantea
 */
class InformacionEstadoDAO extends AbstractDAO
{
	function getEntity()
	{
		return new InformacionEstado();
	}
	
	function getInformacionEstadoRecolectado($idRecoleccion) {
		$entity = $this->getEntity();
		$sql = "SELECT * FROM ".$entity->_tablename." WHERE id_recoleccion = '".$idRecoleccion."' ";
		
		// *** Realizar la consulta *** //
		$res = $entity->_db->leer($sql);
		
		return $this->_rs2Entity($res);
	}
	
	
	
}
?>