<?php
include_once BASE_DIR."clases/negocio/clase.ActividadEnviador.php";

class ActividadEnviadorDAO extends AbstractDAO
{
	function getEntity()
	{
		return new ActividadEnviador();
	}
	
	function existeNombreEnvio($idActividad, $nombre) 
	{
		$actividadEnvio = $this->getEntity();
		
		$sql = "SELECT ";
		$sql .= " ae.id ";
		$sql .= "FROM ";
		$sql .= $actividadEnvio->_tablename." ae ";
		$sql .= "WHERE ";
		$sql .= " nombre = '".addslashes($nombre)."'";
		$sql .= " AND baja_logica = '".FALSE_."'";
		$sql .= " AND temporal = '".FALSE_."'";
		
		$resultado = $this->_rs2array($actividadEnvio->_db->leer($sql));
		
		return sizeof($resultado);
	}
	
}
?>
