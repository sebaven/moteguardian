<?php
include_once BASE_DIR."clases/negocio/clase.RecoleccionEjecutada.php";
include_once BASE_DIR."clases/negocio/clase.Recoleccion.php";
include_once BASE_DIR."clases/negocio/clase.Tarea.php";
include_once BASE_DIR."clases/negocio/clase.Central.php";
include_once BASE_DIR."clases/negocio/clase.EstadoTarea.php";

class RecoleccionEjecutadaDAO extends AbstractDAO
{
	function getEntity()
	{
		return new RecoleccionEjecutada();
	}

	function getEjecucionesMonitoreo($idCentral, $soloErroneas, $idRecoleccion){		
		$recoleccion = new Recoleccion($idRecoleccion);
				
		$sql = "SELECT ";
		$sql .=		"re.id as id_ejecucion, ";
		$sql .= 	"re.fecha as fecha_inicio, ";
		$sql .= 	"re.pid as pid, ";
		$sql .= 	"COUNT( t.id ) AS cantidad_archivos ";
		$sql .=	"FROM recoleccion r ";
		$sql .=		"INNER JOIN recoleccion_ejecutada re ON r.id = re.id_recoleccion ";
		
		if($recoleccion->manual == FALSE_) {
			$sql .=	"INNER JOIN central c ON c.id_recoleccion = r.id ";
		} else {
			$sql .=	"INNER JOIN central_recoleccion_manual crm ON crm.id_recoleccion = r.id ";
			$sql .=	"INNER JOIN central c ON crm.id_central = c.id ";
		}
		
		$sql .=		"LEFT JOIN tarea t ON (t.id_central = c.id AND t.id_recoleccion_ejecutada = re.id) ";				
		$sql .= "WHERE r.baja_logica = '".FALSE_."' ";
		$sql .= 	"AND r.temporal = '".FALSE_."' ";
		$sql .= 	"AND c.baja_logica = '".FALSE_."' ";		
		$sql .= 	"AND re.baja_logica = '".FALSE_."' ";
		$sql .= 	"AND (t.baja_logica = '".FALSE_."' OR t.baja_logica IS NULL)";
		$sql .= 	"AND c.id = '".$idCentral."' ";
					
		if($nombreRecoleccion) {
			$sql .= "AND r.nombre LIKE '%".addEscapeosParaLike($nombreRecoleccion)."%' ";
		}
		
		if($fechaDesde){
			$sql .= "AND re.fecha >= '".$fechaDesde."' ";
		}
		
		if($fechaHasta){
			$sql .= "AND re.fecha <= '".$fechaHasta."' ";
		}
		
		if($soloErroneas==TRUE_){
			
			$sql .= "AND EXISTS ( SELECT * FROM estado_tarea et WHERE et.id_tarea=t.id AND et.baja_logica='".FALSE_."' AND et.fallo='".TRUE_."' )";			 				
		}
		
		$sql .= "GROUP BY re.id, re.fecha ";	
				
		$res = $this->getEntity()->_db->leer($sql);
		return $this->_rs2Collection($res);
	}
}
?>
