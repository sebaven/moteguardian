<?php
include_once BASE_DIR."clases/negocio/clase.CentralRecoleccionManual.php";

class CentralRecoleccionManualDAO extends AbstractDAO
{
	function getEntity()
	{
		return new CentralRecoleccionManual();
	}
	
	function getByIdCentralIdRecoleccionManual($id_central, $id_recoleccion) {
		$entity = $this->getEntity();
		
		$sql = "SELECT ";
		$sql .= 	"id ";
		$sql .= "FROM ".$entity->_tablename." ";
		$sql .= "WHERE id_central = '".addslashes($id_central)."' ";
		$sql .= 	"AND id_recoleccion = '".addslashes($id_recoleccion)."' ";
		
		$res = $entity->_db->leer($sql);
		
		$resultados = $this->_rs2Collection($res);
		return $resultados[0];
	}
}
?>