<?php
include_once BASE_DIR."clases/negocio/clase.HostEnvio.php";

class HostEnvioDAO extends AbstractDAO
{
	function getEntity()
	{
		return new HostEnvio();
	}
	
	function getHostEnvio($id_host, $id_envio){
		$entity = $this->getEntity();
		
		$sql = "SELECT ";
		$sql .= 	"id ";
		$sql .= "FROM ".$entity->_tablename." ";
		$sql .= "WHERE id_host = '".$id_host."' ";
		$sql .= 	"AND id_envio = '".$id_envio."' ";
		
		$res = $entity->_db->leer($sql);
		
		$resultados = $this->_rs2Collection($res);
		return $resultados[0];
	}
}
?>