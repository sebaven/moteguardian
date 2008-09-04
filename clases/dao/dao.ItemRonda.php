<?php
include_once BASE_DIR ."clases/negocio/clase.ItemRonda.php";

class ItemRondaDAO extends AbstractDAO
{
	function getEntity()
	{
		return new ItemRonda();
	}
	
	function getSqlItemRonda($id_ronda) 
	{		
		$sql = "SELECT ";		
		$sql .= 	"ir.id, ";
		$sql .= 	"ir.orden, ";
		$sql .= 	"ir.duracion, ";
		$sql .= 	"s.descripcion as sala ";
		$sql .= "FROM item_ronda ir ";
		$sql .= 	"INNER JOIN sala s ON s.id = ir.id_sala ";
		$sql .= "WHERE s.baja_logica = '".FALSE_."' ";		
		$sql .= 	"AND ir.id_ronda = '".addslashes($id_ronda)."' ";
		$sql .= 	"AND ir.baja_logica = '".FALSE_."' ";
		
		return $sql;		
	}
}

?>