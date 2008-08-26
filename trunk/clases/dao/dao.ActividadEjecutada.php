<?php
include_once BASE_DIR."clases/negocio/clase.ActividadEjecutada.php";
include_once BASE_DIR."clases/negocio/clase.Actividad.php";
include_once BASE_DIR."clases/negocio/clase.Tarea.php";
include_once BASE_DIR."clases/negocio/clase.EstadoTarea.php";

class ActividadEjecutadaDAO extends AbstractDAO
{
	function getEntity()
	{
		return new ActividadEjecutada();
	}

	function getCantidadArchivosPorEjecucion($nombre='', $fecha_desde='', $fecha_hasta='', $soloErroneas = FALSE_)
	{		
		$actividad = new Actividad();
		$actividadEjecutada = $this->getEntity();
		$tarea = new Tarea();
		$estadoTarea = new EstadoTarea();
		
		$sql = "SELECT ";
		$sql .= 	"a.nombre as nombre_actividad, ";
		$sql .=     "CONCAT(ae.fecha,'<a onclick=window.open(\"batch/log/nucleo_srdf_',ae.pid,'.log\",\"_blank\",\"width=550px,height=350px,resizable=1,scrollbars=1\") style=\"cursor:pointer; font-style: italic; font-weight:bold;\">',' (ver log) ','</a>') AS fecha_ejecucion, ";
		$sql .= 	"ae.id, ";		
		$sql .= 	"count(t.id) as cant_archivos ";
		$sql .= "FROM ".$actividadEjecutada->_tablename." ae ";
		$sql .= 	"LEFT JOIN ".$tarea->_tablename." t ON ae.id = t.id_actividad_ejecutada ";
		$sql .= 	"INNER JOIN ".$actividad->_tablename." a ON  a.id = ae.id_actividad ";
		$sql .= "WHERE a.baja_logica = '".FALSE_."' ";
		$sql .= 	"AND ae.baja_logica = '".FALSE_."' ";		
		$sql .= 	"AND a.temporal = '".FALSE_."' ";		
		$sql .= 	"AND (t.baja_logica = '".FALSE_."' ";
		$sql .=		"OR t.baja_logica is NULL) ";
		if($soloErroneas==TRUE_){
			$sql .= "AND (t.id in (SELECT t.id FROM ".$tarea->_tablename." t INNER JOIN ".$estadoTarea->_tablename." et ON et.id_tarea = t.id AND et.fallo='1' ) ";
			$sql .= "OR t.id IS NULL ) ";
		}
		
		if($nombre) {
			$sql .= "AND a.nombre LIKE '%".$nombre."%'";
		}
		if($fecha_desde) {
			$sql .= "AND ae.fecha >= '".$fecha_desde."' ";
		}
		if($fecha_hasta) {
			$sql .= "AND ae.fecha <= '".$fecha_hasta."' ";
		}
		
		$sql .= "GROUP BY a.nombre, ae.fecha ";
		$sql .= "ORDER BY a.nombre, ae.fecha ";
		
		return $this->_rs2array( $this->getEntity()->_db->leer($sql) );
	}
	
}
?>
