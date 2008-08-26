<?php
include_once BASE_DIR."clases/negocio/clase.Tarea.php";
include_once BASE_DIR."clases/negocio/clase.EstadoTarea.php";

class TareaDAO extends AbstractDAO
{
	function getEntity()	
	{
		return new Tarea();
	}
	
	function getTareasByIdActividadEjecutada($id_actividad_ejecutada, $soloErroneas = FALSE_)
	{
		$estadoTarea = new EstadoTarea();		
		$tarea = $this->getEntity();
		
		$sql = "SELECT ";
		$sql .= 		" id ";
		$sql .= "FROM ".$this->getEntity()->_tablename." t ";
		$sql .= "WHERE id_actividad_ejecutada = '".$id_actividad_ejecutada."' AND baja_logica = '".FALSE_."' ";
		if($soloErroneas==TRUE_) $sql .= "AND t.id in (SELECT t.id FROM ".$tarea->_tablename." t INNER JOIN ".$estadoTarea->_tablename." et ON et.id_tarea = t.id AND et.fallo='1' ) ";
		
		return $this->_rs2array($this->getEntity()->_db->leer($sql));
	}


	function getSqlEstadoTareas($id_tarea = '')
	{			
		$w = array();
		
		if ($id_tarea) {
			$w[] = "t.id_tarea =" . addslashes($id_tarea);						
		}
			
		$sql  = " SELECT t.nombre_original, t.nombre_actual,  ";
		$sql .=	" t.timestamp_ingreso , t.fallo, "; 
        $sql .= " CONCAT('<a onclick=window.open(\"batch/log/nucleo_srdf_',t.pid,'.log\",\"_blank\",\"width=550px,height=350px,resizable=1,scrollbars=1\")>',d.nombre_estado,'</a>') AS nombre_estado ";
		$sql .=	"FROM estado_tarea t ";
		$sql .= "INNER JOIN datos_proceso d ON t.id_datos_proceso = d.id ";
		$sql .= "WHERE t.baja_logica = '".FALSE_."' ";
						
		if ($w)
		{
			$sql .= " AND ".implode(' AND ', $w) . " ";
		}
				
		return $sql;		
	}	
	
	function archivoYaRecolectado($id_actividad,$ubicacion,$nombre) {
		$sql = "SELECT * FROM tarea WHERE id_actividad = '".addslashes($id_actividad)."' ";
		$sql.= "AND nombre_original = '".addslashes($nombre)."' AND ubicacion_original = '".addslashes($ubicacion)."' ";
		
		$entity = $this->getEntity();
		$res = $entity->_db->leer($sql);
		
		if($entity->_db->numero_filas($res)) return true;
		else return false;
	}
	
	
	/*
	 * CUIDADO, NO DEVUELVE TAREAS, NI OBJECTOS, SINO UN ARRAY CON LOS DATOS DEL ARCHIVO PARA ENVIARLO
	 */
	function getArchivosAEnviar($id_actividad,$id_actividad_envio) {
		$sql = "SELECT *,t.id AS t_id FROM tarea t ";
		$sql.= "JOIN actividad_ejecutada ae ON t.id_actividad_ejecutada = ae.id ";
		$sql.= "WHERE ae.id_actividad = '".addslashes($id_actividad)."' AND t.esperando_envio = '".TRUE_."'";

		$entity = $this->getEntity();
		$db = $entity->_db;
		$res = $db->leer($sql);
		
		$archivos = array();

		if($db->numero_filas($res) == 0) {
			return $archivos;
		}
		// Iterar por cada uno de las tareas
		
		while($r = $db->reg_array($res))
		{
			// Busco que haya registro de alguna vez haberse enviado bien
			$sql = " SELECT * FROM estado_tarea et ";
			$sql.= " JOIN datos_proceso dt ON et.id_datos_proceso = dt.id ";
			$sql.= " WHERE dt.id_proceso_actividad_envio = '".$id_actividad_envio."' ";
			$sql.= " AND et.fallo = '".FALSE_."' AND et.id_tarea = '".$r['t_id']."' ";
			$rs = $db->leer($sql);
			if(!$rs) continue;
			if($db->numero_filas($rs) > 0) {
				// Ya se mando, ok
				continue;
			}
			
			// Ahora busco los datos para la recoleccion
			$sql = " SELECT * FROM estado_tarea WHERE id_tarea = '".$r['t_id']."' AND esperando_envio = '".TRUE_."' ";
			$rs = $db->leer($sql);
			if((!$rs)||($db->numero_filas($rs) == 0)) {
				// no se encontro
				continue;
			}
			
			$estadoTarea = $db->reg_array($rs);
			$archivos[] = array('archivoOriginal'=>$estadoTarea['nombre_original'],
								'archivoTemporal'=>$estadoTarea['nombre_actual'],
								'ubicacionOriginal'=>"",
								'ubicacionTemporal'=>$estadoTarea['ubicacion'],
								'idTarea'=> $r['t_id']
								);
		}
		
		return $archivos;		
		
	}

	function FilterByPendientesPorActividad($id_actividad) {
		$sql = "SELECT t.* FROM tarea t JOIN actividad_ejecutada ae ON t.id_actividad_ejecutada = ae.id ";
		$sql.= "WHERE ae.id_actividad = '".$id_actividad."' AND t.pendiente_recoleccion = '".TRUE_."'";
		
		$entity = $this->getEntity();
		
		$res = $entity->_db->leer($sql);
		
		return $this->_rs2Collection($res);
	}
	
	
}
?>
