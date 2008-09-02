<?php
include_once BASE_DIR."clases/negocio/clase.Planificacion.php";

class PlanificacionDAO extends AbstractDAO
{
	function getEntity()
	{
		return new Planificacion();
	}
	
	function filterByFecha($fechaDesde,$fechaHasta) {		
		// Si se pasa un rango inconsistente no se devuelve ninguna planificación
		if($fechaDesde->toString() > $fechaHasta->toString()) {
			return array();
		}		
		
		$sql  = "SELECT * ";
		$sql .= "FROM planificacion ";
		$sql .= "WHERE baja_logica = '".FALSE_."' ";
		$sql .= 	"AND fecha_vigencia <= '".$fechaDesde->toString()."' ";
		$sql .= 	"AND (";

		$diaDesde = $fechaDesde->diaDeLaSemana;
		$diaHasta = $fechaHasta->diaDeLaSemana;
		$nombreDiaDesde = FechaHelper::diaDeLaSemanaToString($diaDesde);
		$nombreDiaHasta = FechaHelper::diaDeLaSemanaToString($diaHasta);
		$conector = ($nombreDiaDesde==$nombreDiaHasta)?"AND":"OR";
		
		// Busqueda por fecha absoluta
		$sql.= "( dia_absoluto IS NOT NULL AND dia_semana IS NULL AND hora IS NOT NULL AND (";
		$sql.= "DATE_ADD(dia_absoluto,INTERVAL hora HOUR_SECOND) > '".$fechaDesde->toString()."' ";
		$sql.= "AND DATE_ADD(dia_absoluto,INTERVAL hora HOUR_SECOND) <= '".$fechaHasta->toString()."')";
		$sql.= ")";

		// Busqueda por fecha semanal
		$sql.= " OR (dia_absoluto IS NULL AND dia_semana IS NOT NULL AND hora IS NOT NULL AND (";
		$condiciones = array();
		$dias_enteros = FechaHelper::fechaDiffDiasCompletos($fechaDesde,$fechaHasta);
		if(!empty($dias_enteros)) {
			$condiciones[] = "(dia_semana IN ('".implode("','",$dias_enteros)."') )";
			// Si hubo algun dia entero, son todos los diariamente
		}
		$condiciones[] = "(dia_semana = '".FechaHelper::diaDeLaSemanaToString($diaDesde)."' AND hora > '".$fechaDesde->timeToString()."' ".
						 $conector.
						 " dia_semana = '".FechaHelper::diaDeLaSemanaToString($diaHasta)."' AND hora <= '".$fechaHasta->timeToString()."')";
		$sql.= implode(" OR ",$condiciones);
		$sql.= " )) ";
		
		// Busqueda por fecha diaria
		$sql.= " OR (dia_semana IS NULL AND dia_absoluto IS NULL AND hora IS NOT NULL ";
		if(empty($dias_enteros)) {
			$sql.= " AND (hora > '".$fechaDesde->timeToString()."' ".$conector." hora <= '".$fechaHasta->timeToString()."') ";
		}
		$sql.= ") ";
		
		// Cierro query global
		$sql.= ")";
		

		$entity = $this->getEntity();
		// *** Realizar la consutla y devolver el resultado *** //
		$res = $entity->_db->leer($sql);

		// *** Convertir el record set en un array asociativo y devolverlo *** 
		return $this->_rs2Collection($res);
	}
}
?>