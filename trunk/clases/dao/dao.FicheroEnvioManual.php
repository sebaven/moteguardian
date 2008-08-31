<?php
include_once BASE_DIR."clases/negocio/clase.FicheroEnvioManual.php";

class FicheroEnvioManualDAO extends AbstractDAO
{
	function getEntity()
	{
		return new FicheroEnvioManual();
	}
	
	function getByIdFicheroIdEnvioManual($id_fichero, $id_envio) {
		$entity = $this->getEntity();
		
		$sql = "SELECT ";
		$sql .= 	"id ";
		$sql .= "FROM ".$entity->_tablename." ";
		$sql .= "WHERE id_fichero = '".$id_fichero."' ";
		$sql .= 	"AND id_envio = '".$id_envio."' ";
		
		$res = $entity->_db->leer($sql);
		
		$resultados = $this->_rs2Collection($res);
		return $resultados[0];
	}
}
?>