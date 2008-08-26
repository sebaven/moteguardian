<?php
include_once BASE_DIR."clases/negocio/clase.Planificacion.php";


class PlanificacionDAO extends AbstractDAO
{
	function getEntity()
	{
		return new Planificacion();
	}
	
	function filterByFecha($fechaDesde,$fechaHasta) {
		if($fechaDesde->toString() > $fechaHasta->toString()) {
			return array();
		}
		
		$sql = "SELECT * FROM planificacion WHERE baja_logica = '".FALSE_."' AND (";

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

	/**
	 *  Devuelve los Planificacion para una Actividad en Particular
	 */	
	function getSqlPlanificaciones($params)
	{			
		$sql  = "SELECT p.hora as hora, p.id as id, p.dia_semana as dia_semana, p.dia_absoluto as dia_absoluto ";		
		$sql .=	"FROM planificacion p ";
		$sql .= "INNER JOIN actividad a ON a.id = p.id_actividad ";
		$sql .= "WHERE a.id ='".addslashes($params['id'])."' ";
		$sql .= "AND p.baja_logica ='".FALSE_."'";

		return $sql;
	}	

	/**
	 * Devuelve la menor fecha (y hora) de recolección de la actividad si tiene alguna
	 * planificación absoluta de recolección
	 * 
	 * @param  $id de la actividad
	 * @return fecha y hora de la menor planificación absoluta de recolección o null si no 
	 * tiene planificaciones absolutas
	 */
	function getMenorPlanificacionAbsoluta($idActividad){
		$planificacion = $this->getEntity();
		
		$sql = "SELECT ";
		$sql .= 	"dia_absoluto, ";
		$sql .= 	"hora ";
		$sql .= "FROM ".$planificacion->_tablename." ";
		$sql .= "WHERE id_actividad = '".$idActividad."' ";
		$sql .= 	"AND dia_absoluto IS NOT NULL ";
		$sql .=		"AND baja_logica = '".FALSE_."' ";
		$sql .= 	"AND ( (dia_absoluto = (SELECT MIN(dia_absoluto) FROM ".$planificacion->_tablename." WHERE id_actividad = '".$idActividad."' AND baja_logica = '".FALSE_."')) ";
		$sql .=			"AND (hora = (SELECT MIN(hora) FROM ".$planificacion->_tablename." WHERE id_actividad = '".$idActividad."' AND baja_logica = '".FALSE_."' AND 	dia_absoluto = (SELECT MIN(dia_absoluto) FROM ".$planificacion->_tablename." WHERE id_actividad = '".$idActividad."' AND baja_logica = '".FALSE_."')) ) ) ";
		$sql .= "LIMIT 0,1";
		
		$res = $planificacion->_db->leer($sql);
		return $this->_rs2array($res);
	}
	
	function tieneRecoleccionesPeriodicas($idActividad) {
		$planificacion = $this->getEntity();
		
		$sql = "SELECT ";
		$sql .= " count(id_actividad) AS cantidad ";
		$sql .= "FROM ".$planificacion->_tablename." ";
		$sql .= "WHERE id_actividad = '".$idActividad."' ";
		$sql .= 	"AND dia_absoluto IS NULL ";
		$sql .=		"AND baja_logica = '".FALSE_."' ";
		
		$res = $planificacion->_db->leer($sql);
		$array = $this->_rs2array($res);
		if($array[0]['cantidad'] > 0){
			return TRUE_;
		} else {
			return FALSE_;
		}
	}
	
	/*** Devuelve los Planificacion para una Actividad y sus Envios en Particular
    */
	
	function getSqlPlanificacionesAE($params)
	{			
		$sql  = "SELECT p.hora as hora, p.id as id, p.dia_semana as dia_semana, p.dia_absoluto as dia_absoluto ";		
		$sql .=	"FROM planificacion p, actividad_envio ae,actividad a, tecnologia_enviador t ";
		$sql .= "WHERE ";
		$sql .= "ae.id_actividad = a.id AND ";
		$sql .= "ae.id = p.id_actividad_envio AND ";
		$sql .= "ae.id_tecnologia_enviador = t.id AND ";
		$sql .= "p.id_actividad_envio ='".addslashes($params['id'])."' ";
		$sql .= "AND p.baja_logica ='".FALSE_."'";
	
		return $sql;
	}	
}
?>