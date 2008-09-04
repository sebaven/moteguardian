<?php
include_once BASE_DIR ."clases/negocio/clase.Ronda.php";

class RondaDAO extends AbstractDAO
{
	function getEntity()
	{
		return new Ronda();
	}
	
	function getSQLRondas($params)
	{				
		$sql = "SELECT ";		
		$sql .=		"distinct (p.id_ronda) as id, ";
		$sql .= 	"p.dia_absoluto, ";
		$sql .= 	"p.dia_semana, ";
		$sql .= 	"p.hora, ";
		$sql .= 	"p.fecha_vigencia, ";
		$sql .=		"g.nombre as nombre_guardia ";		
		$sql .= "FROM planificacion p ";
		$sql .= 	"INNER JOIN ronda r ON p.id_ronda = r.id ";
		$sql .= 	"INNER JOIN guardia g ON r.id_guardia = g.id ";
		$sql .= "WHERE r.baja_logica = '".FALSE_."' ";
		$sql .= 	"AND g.baja_logica = '".FALSE_."' ";
		
		if(count($params['planificaciones'])>0){ 
			$sql .=	"AND p.id IN ( ".implode(" , ", $params['planificaciones'])." ) ";		
		} else {
			$sql .=	"AND p.id IN ( 999999 ) ";
		}
		if($params['id_guardia']) $sql .= "AND g.id = '".addslashes($params['id_guardia'])."' ";		
		
		return $sql;
	}
}

?>