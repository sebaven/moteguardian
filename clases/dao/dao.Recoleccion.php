<?php
include_once BASE_DIR."clases/negocio/clase.Recoleccion.php";

/**
 * @author cgalli
 */
class RecoleccionDAO extends AbstractDAO
{
	function getEntity()
	{
		return new Recoleccion();
	}
	
	function getSqlFindByParameters($values = '') {
		$w = array();
		$i = 0;
		
		$w[$i] = ( "r.baja_logica= '".FALSE_."'" );		
		$i++;
		
		$w[$i] = ( "c.baja_logica= '".FALSE_."'" );		
		$i++;

		$w[$i] = ( "r.temporal= '".FALSE_."'" );		
		$i++;
		
		if ($values['nombre_recoleccion']){			
			$w[$i] = "r.nombre LIKE '%" . addEscapeosParaLike($values['nombre_recoleccion']) . "%'";
			$i++;
		}			
		if ($values['nombre_central']){			
			$w[$i] = "c.nombre LIKE '%" . addEscapeosParaLike($values['nombre_central']) . "%'";
			$i++;
		}		
		if ($values['procesador']){			
			$w[$i] = "c.procesador LIKE '%" . addEscapeosParaLike($values['procesador']) . "%'";
			$i++;
		}
			
						
		if ($values['tecnologia_recolector']) {
			$w[$i] = "c.id_tecnologia_recolector = '" . addslashes($values['tecnologia_recolector']) . "'";
			$i++;
		}
			
		if ($values['tecnologia_central']) {
			$w[$i] = "id_tecnologia_central = '" . addslashes($values['tecnologia_central']) . "'";
			$i++;
		}
			
		if($values['solo_habilitadas']) {
			$w[$i] = "r.habilitado = '" .TRUE_."'";						
			$i++;
		}
			
		$sql  = "SELECT DISTINCT(r.id), "; 
		$sql .= "		r.nombre AS nombre_recoleccion, ";
		$sql .= "		r.habilitado ";		
		$sql .=	"FROM central c ";
		$sql .= "INNER JOIN tecnologia_recolector tr ON tr.id = c.id_tecnologia_recolector ";
		$sql .= "INNER JOIN tecnologia_central tc ON tc.id = c.id_tecnologia_central ";
		$sql .= "INNER JOIN recoleccion r ON r.id = c.id_recoleccion ";

		if ($w)
		{
			$sql .= "WHERE " . implode(' AND ', $w) . " ";
		}

		return $sql;
	}
	
	function getSqlEstadisticaIntentosRecoleccion($values = '') {
		$w = array();
		$i = 0;
		
		if($values['tipo_recoleccion']) {
			switch ($values['tipo_recoleccion']) {
				case "manual":
					 $w[$i] = "r.manual = '" .TRUE_."'";						
					 $i++;
					 break;
				
				case "automatica":
					 $w[$i] = "r.manual = '" .FALSE_."'";						
					 $i++;
					 break;
			}
		}

		if($values['fecha']) {
			$desde = $values['fecha']." 00:00";
			$hasta= $values['fecha']." 23:59";
			
			$w[$i] = "t.fecha_terminada >= '" .$desde."'";						
			$i++;
			
			$w[$i] = "t.fecha_terminada <= '" .$hasta."'";						
			$i++;
		}
	
		if($values['id_tec_recolector']) {
			$w[$i] = "c.id_tecnologia_recolector = '" .$values['id_tec_recolector']."'";						
			$i++;
		}
			
		$sql  = 'SELECT count( distinct r.id ) AS CANTIDAD, tr.nombre_tecnologia AS TECNOLOGIA, IF( r.manual, \'Manual\', \'Automatica\' ) AS TIPO, t.fecha_terminada AS FECHA ';
		$sql .= 'FROM tarea AS t ';
		$sql .= 'INNER JOIN recoleccion r ON r.id = t.id_recoleccion ';
		$sql .= 'INNER JOIN central c ON c.id = t.id_central ';
		$sql .= 'INNER JOIN tecnologia_recolector tr ON c.id_tecnologia_recolector = tr.id ';
		
		if ($w)
		{
			$sql .= "WHERE " . implode(' AND ', $w) . " ";
		}
		
		$sql  .= ' GROUP BY t.fecha_terminada, tr.nombre_tecnologia, r.manual';
		
		
		return $sql;
	}
	
	function getNombreRecoleccionMenosLaPasadaPorParametro($id,$nombre) {		
		$entity = $this->getEntity();
		
		$sql = "SELECT nombre ";
		$sql .= "FROM ".$entity->_tablename." r ";
		$sql .= "WHERE r.id <> '".$id."' ";
		$sql .= 	"AND r.baja_logica = '".FALSE_."' ";
		$sql .= 	"AND r.temporal = '".FALSE_."' ";
		$sql .= 	"AND r.nombre = '".addslashes($nombre)."' ";
		
		$res = $entity->_db->leer($sql);
		return $this->_rs2Collection($res);
	}
	
	function getRecoleccionesSinAsignar() {
		$entity = $this->getEntity();
		
		$sql = "SELECT nombre, id ";
		$sql .= "FROM ".$entity->_tablename." r ";
		$sql .= "WHERE (r.id_actividad IS NULL OR r.id_actividad IN (SELECT id FROM actividad WHERE baja_logica = '".TRUE_."' OR temporal = '".TRUE_."') ) ";
		$sql .= 	"AND r.baja_logica = '".FALSE_."' ";
		$sql .= 	"AND r.manual = '".FALSE_."' ";
		$sql .= 	"AND r.temporal = '".FALSE_."' ";		
		
		$res = $entity->_db->leer($sql);
		return $this->_rs2Collection($res);
	}

	function getRecoleccionesAgregadas($id) {
		
		$entity = $this->getEntity();
		
		$sql = "SELECT nombre, id ";
		$sql .= "FROM ".$entity->_tablename." r ";
		$sql .= "WHERE r.id_actividad = '".addslashes($id)."'";
		$sql .= 	"AND r.baja_logica = '".FALSE_."' ";
		$sql .= 	"AND r.manual = '".FALSE_."' ";
		$sql .= 	"AND r.temporal = '".FALSE_."' ";				
		
		$res = $entity->_db->leer($sql);
		return $this->_rs2Collection($res);
	}
	
	function getAll($orderby = '')
	{
		$entity = $this->getEntity();
		
		// *** Armar la lista de campos en el orden que aparecen en $this->_fields *** //
		$campos = "id";
		foreach($entity->_fields as $field => $value)
		{
			$campos .= ", $field";
		}  	  
		
		// *** Armar la consulta *** //
		$sql  = "SELECT ";
		$sql .= $campos;
		$sql .= " FROM $entity->_tablename ";
		$sql .= " WHERE baja_logica = '".FALSE_."' ";
		$sql .= " AND manual = '".FALSE_."' ";
		$sql .=	"AND temporal = '".FALSE_."' ";
		
		if ($orderby)
			$sql .= "ORDER BY $orderby;";
			
		
		// *** Realizar la consutla y devolver el resultado *** //
		$res = $entity->_db->leer($sql);
		
		// *** Convertir el record set en un array asociativo y devolverlo *** 
		return $this->_rs2Collection($res);
	}
	
	/**
	 * Devuelve un array con las recolecciones que se deben lanzar para recoleccion entre esas 2 fechas, la 1ra exclusive y la 2da inclusive
	 */
	function filterByFecha($fechaDesde,$fechaHasta) {
		$planificacionDAO = new PlanificacionDAO();
		$planificaciones = $planificacionDAO->filterByFecha($fechaDesde,$fechaHasta);
		$recoleccionesIds = array();
		$recolecciones = array();
		foreach($planificaciones as $planificacion) {
			if(!(empty($planificacion->id_recoleccion))) {
				// Planificacion de recoleccion
				if(!isset($recoleccionesIds[$planificacion->id_recoleccion])) {
					// Filtro por si se disparan por diversas planificaciones la misma recoleccion
					$recoleccionesIds[$planificacion->id_recoleccion] = $planificacion->id_recoleccion;
					$recoleccion = new Recoleccion();
					$recoleccion->load($planificacion->id_recoleccion);
					if(($recoleccion->temporal == FALSE_)&&($recoleccion->baja_logica == FALSE_)) {
						$recolecciones[] = $recoleccion;
					}
				}
			}
		}
		
		return $recolecciones;
	}
	
	function getSQLRecoleccionesConFallas($params){
		$sql .= "SELECT ";
		$sql .= 	"r.id as id_recoleccion, ";
		$sql .= 	"DISTINCT(t.id) as id, ";
		$sql .= 	"t.nombre as nombre, ";
		$sql .= 	"et.timestamp_ingreso as fecha_de_ejecucion, ";
		$sql .=		"CONCAT('<img onclick=\"popup(\'archivo\',\'acciones/tareas/descargar.php?archivo=../../batch/log/nucleo_srdf_',et.pid,'.log\',\'400\',\'400\')\" src=\"imagenes/b_search.png\" >') AS log ";
		$sql .= "FROM recoleccion r ";
		$sql .=		"INNER JOIN informacion_estado ie ON r.id = ie.id_recoleccion ";
		$sql .=		"INNER JOIN estado_tarea et ON et.id_informacion_estado = ie.id ";
		$sql .=		"INNER JOIN tarea t ON t.id = et.id_tarea ";
		$sql .= "WHERE t.esperando_envio = '".TRUE_."' ";
		$sql .= 	"AND et.fallo = '".TRUE_."' ";
		$sql .= 	"AND r.baja_logica = '".FALSE_."' ";
		$sql .=		"AND r.temporal = '".FALSE_."' ";
		$sql .=		"AND et.baja_logica = '".FALSE_."' ";
		$sql .= 	"AND t.baja_logica = '".FALSE_."' ";

		if($params['nombre']) $sql .= "AND r.nombre LIKE '%".addEscapeosParaLike($values['nombre']). "%' ";
		if($params['nombre_de_fichero']) $sql .= "AND t.nombre LIKE '%".addEscapeosParaLike($values['nombre_de_fichero']). "%' ";
		if($params['fecha_desde']) $sql.= "AND et.timestamp_ingreso >= '".$params['fecha_desde']." ".$params['id_horas_desde'].":".$params['id_minutos_desde'].":00' ";  
		if($params['fecha_hasta']) $sql.= "AND et.timestamp_ingreso <= '".$params['fecha_hasta']." ".$params['id_horas_hasta'].":".$params['id_minutos_hasta'].":00' ";  
		
		return $sql;
	}
	
	/**
	 * 
	 */
	function getCircuitosCompletos($params) {
		
		$entity = $this->getEntity();
		
		$sql = 	"SELECT ";
		$sql .=		"COUNT(DISTINCT r.id) as cantidad_circuitos, ";
		$sql .= 	"r.nombre as nombre_recoleccion, ";
		$sql .= 	"r.id as id_recoleccion, ";
		$sql .= 	"r.habilitado as habilitado_recoleccion, ";
		$sql .= 	"plantilla.nombre as nombre_plantilla, ";
		$sql .= 	"plantilla.id as id_plantilla, ";
		$sql .= 	"COUNT(DISTINCT c.id) as cantidad_centrales, ";
		$sql .= 	"COUNT(DISTINCT planificacion.id) as cantidad_planificaciones, ";
		$sql .= 	"COUNT(DISTINCT np.id) as cantidad_filtros, ";
		$sql .= 	"COUNT(DISTINCT e.id) as cantidad_envios ";
		$sql .= "FROM central c ";
		$sql .= 	"INNER JOIN recoleccion r ON c.id_recoleccion = r.id ";
		$sql .= 	"INNER JOIN actividad a ON r.id_actividad = a.id ";
		$sql .= 	"INNER JOIN plantilla ON plantilla.id = a.id_plantilla ";
		$sql .=		"INNER JOIN nodo_plantilla np ON plantilla.id = np.id_plantilla ";
		$sql .=		"INNER JOIN envio e ON e.id_recoleccion = r.id ";		
		$sql .=		"INNER JOIN host_envio he ON e.id = he.id_envio ";		
		$sql .=		"INNER JOIN host h ON h.id = he.id_host ";		
		$sql .= 	"LEFT JOIN planificacion ON planificacion.id_recoleccion = r.id ";
		$sql .= "WHERE r.baja_logica = '".FALSE_."' ";
		$sql .= 	"AND r.manual = '".FALSE_."' ";
		$sql .= 	"AND r.temporal = '".FALSE_."' ";
		$sql .= 	"AND a.baja_logica = '".FALSE_."' ";
		$sql .= 	"AND plantilla.baja_logica = '".FALSE_."' ";
		$sql .= 	"AND plantilla.temporal = '".FALSE_."' ";
		$sql .= 	"AND np.baja_logica = '".FALSE_."' ";
		$sql .= 	"AND e.baja_logica = '".FALSE_."' ";
		$sql .= 	"AND e.temporal = '".FALSE_."' ";
		$sql .= 	"AND h.baja_logica = '".FALSE_."' ";
		$sql .=		"AND c.baja_logica = '".FALSE_."' ";
		$sql .=		"AND (planificacion.baja_logica = '".FALSE_."' OR planificacion.baja_logica IS NULL) ";		
		
		if($params['central']) 						$sql .= "AND c.nombre LIKE '%".trim(addEscapeosParaLike($params['central']))."%' ";
		if($params['procesador']) 					$sql .= "AND c.procesador LIKE '%".trim(addEscapeosParaLike($params['procesador']))."%' ";
		if($params['id_tecnologia_central']) 		$sql .= "AND c.id_tecnologia_central = '".addslashes($params['id_tecnologia_central'])."' ";
		if($params['id_tecnologia_recolector']) 	$sql .= "AND c.id_tecnologia_recolector = '".addslashes($params['id_tecnologia_recolector'])."' ";
		if($params['id_tecnologia_enviador']) 		$sql .= "AND h.id_tecnologia_enviador = '".addslashes($params['id_tecnologia_enviador'])."' ";
		if($params['recoleccion']) 					$sql .= "AND r.nombre LIKE '%".trim(addEscapeosParaLike($params['recoleccion']))."%' ";
		if($params['actividad']) 					$sql .= "AND a.nombre LIKE '%".trim(addEscapeosParaLike($params['actividad']))."%' ";
		if($params['plantilla']) 					$sql .= "AND plantilla.nombre LIKE '%".trim(addEscapeosParaLike($params['plantilla']))."%' ";
		if($params['envio']) 						$sql .= "AND e.nombre LIKE '%".trim(addEscapeosParaLike($params['envio']))."%' ";
		if($params['host']) 						$sql .= "AND h.nombre LIKE '%".trim(addEscapeosParaLike($params['host']))."%' ";
				
		$sql .=	"GROUP BY r.id, r.nombre, r.habilitado, plantilla.nombre";
				
		$res = $entity->_db->leer($sql);				
	
		return $this->_rs2array($res);		
	}
	
	function getCircuitosIncompletosRecoleccionesSinActividadSinEnvio($params){
		
		$entity = $this->getEntity();
		
		$sql = 	"SELECT ";		
		$sql .= 	"r.nombre as nombre_recoleccion, ";
		$sql .= 	"r.id as id_recoleccion, ";
		$sql .= 	"r.habilitado as habilitado_recoleccion ";				
		$sql .= "FROM recoleccion r ";
		$sql .=		"INNER JOIN central c ON c.id_recoleccion = r.id ";				
		$sql .= "WHERE r.baja_logica = '".FALSE_."' ";
		$sql .= 	"AND r.manual = '".FALSE_."' ";
		$sql .= 	"AND r.temporal = '".FALSE_."' ";		
		$sql .= 	"AND (r.id_actividad IS NULL OR r.id_actividad IN (SELECT id FROM actividad WHERE baja_logica = '".TRUE_."' OR temporal = '".TRUE_."') ) ";
		$sql .= 	"AND r.id NOT IN (SELECT id_recoleccion FROM envio WHERE temporal ='".FALSE_."' AND baja_logica = '".FALSE_."') ";
		$sql .=		"AND c.baja_logica ='".FALSE_."'";
				
		if($params['central']) 						$sql .= "AND c.nombre LIKE '%".trim(addEscapeosParaLike($params['central']))."%' ";
		if($params['procesador']) 					$sql .= "AND c.procesador LIKE '%".trim(addEscapeosParaLike($params['procesador']))."%' ";
		if($params['id_tecnologia_central']) 		$sql .= "AND c.id_tecnologia_central = '".addslashes($params['id_tecnologia_central'])."' ";
		if($params['id_tecnologia_recolector']) 	$sql .= "AND c.id_tecnologia_recolector = '".addslashes($params['id_tecnologia_recolector'])."' ";
		if($params['recoleccion']) 					$sql .= "AND r.nombre LIKE '%".trim(addEscapeosParaLike($params['recoleccion']))."%' ";
		
		$sql .=	"GROUP BY r.id, r.nombre, r.habilitado";

		if($params['actividad'] || $params['plantilla'] || $params['envio'] || $params['host'] || $params['id_tecnologia_enviador']) return;

		$res = $entity->_db->leer($sql);
		return $this->_rs2Collection($res);		
	}
	
	function getCircuitosIncompletosRecoleccionesSinActividadConEnvio($params){
		
		$entity = $this->getEntity();
		
		$sql = 	"SELECT ";		
		$sql .= 	"r.nombre as nombre_recoleccion, ";
		$sql .= 	"r.id as id_recoleccion, ";
		$sql .= 	"r.habilitado as habilitado_recoleccion ";				
		$sql .= "FROM recoleccion r ";
		$sql .=		"INNER JOIN central c ON c.id_recoleccion = r.id ";
		$sql .=		"INNER JOIN envio e ON e.id_recoleccion = r.id ";		
		$sql .=		"INNER JOIN host_envio he ON e.id = he.id_envio ";		
		$sql .=		"INNER JOIN host h ON h.id = he.id_host ";						
		$sql .= "WHERE r.baja_logica = '".FALSE_."' ";
		$sql .= 	"AND r.manual = '".FALSE_."' ";
		$sql .= 	"AND r.temporal = '".FALSE_."' ";
		$sql .= 	"AND (r.id_actividad IS NULL OR r.id_actividad IN (SELECT id FROM actividad WHERE baja_logica = '".TRUE_."' OR temporal = '".TRUE_."') ) ";		
		$sql .=		"AND c.baja_logica ='".FALSE_."'";
		$sql .=		"AND e.baja_logica ='".FALSE_."'";
		$sql .= 	"AND h.baja_logica = '".FALSE_."' ";
		$sql .=		"AND e.temporal ='".FALSE_."'";
				
		if($params['central']) 						$sql .= "AND c.nombre LIKE '%".trim(addEscapeosParaLike($params['central']))."%' ";
		if($params['procesador']) 					$sql .= "AND c.procesador LIKE '%".trim(addEscapeosParaLike($params['procesador']))."%' ";
		if($params['id_tecnologia_central']) 		$sql .= "AND c.id_tecnologia_central = '".addslashes($params['id_tecnologia_central'])."' ";
		if($params['id_tecnologia_recolector']) 	$sql .= "AND c.id_tecnologia_recolector = '".addslashes($params['id_tecnologia_recolector'])."' ";
		if($params['id_tecnologia_enviador'])	 	$sql .= "AND h.id_tecnologia_enviador = '".addslashes($params['id_tecnologia_enviador'])."' ";
		if($params['recoleccion']) 					$sql .= "AND r.nombre LIKE '%".trim(addEscapeosParaLike($params['recoleccion']))."%' ";
		if($params['envio']) 						$sql .= "AND e.nombre LIKE '%".trim(addEscapeosParaLike($params['envio']))."%' ";
		if($params['host']) 						$sql .= "AND h.nombre LIKE '%".trim(addEscapeosParaLike($params['host']))."%' ";		
				
		$sql .=	"GROUP BY r.id, r.nombre, r.habilitado";

		if($params['actividad'] || $params['plantilla']) return;
		
		$res = $entity->_db->leer($sql);
			
		return $this->_rs2Collection($res);		
	}
	
	function getCircuitosIncompletosRecoleccionesConActividadSinEnvio($params){
		
		$entity = $this->getEntity();
		
		$sql = 	"SELECT ";		
		$sql .= 	"r.nombre as nombre_recoleccion, ";
		$sql .= 	"r.id as id_recoleccion, ";
		$sql .= 	"r.habilitado as habilitado_recoleccion, ";
		$sql .= 	"plantilla.id as id_plantilla, ";		
		$sql .= 	"plantilla.nombre as nombre_plantilla ";		
		$sql .= "FROM recoleccion r ";
		$sql .=		"INNER JOIN central c ON c.id_recoleccion = r.id ";
		$sql .= 	"INNER JOIN actividad a ON r.id_actividad = a.id ";
		$sql .= 	"INNER JOIN plantilla ON plantilla.id = a.id_plantilla ";						
		$sql .= "WHERE r.baja_logica = '".FALSE_."' ";
		$sql .= 	"AND r.manual = '".FALSE_."' ";
		$sql .= 	"AND r.temporal = '".FALSE_."' ";		
		$sql .= 	"AND r.id NOT IN (SELECT id_recoleccion FROM envio WHERE temporal ='".FALSE_."' AND baja_logica = '".FALSE_."') ";
		$sql .=		"AND c.baja_logica ='".FALSE_."' ";
		$sql .= 	"AND a.baja_logica = '".FALSE_."' ";
		$sql .= 	"AND plantilla.baja_logica = '".FALSE_."' ";
		$sql .= 	"AND plantilla.temporal = '".FALSE_."' ";
				
		if($params['central']) 						$sql .= "AND c.nombre LIKE '%".trim(addEscapeosParaLike($params['central']))."%' ";
		if($params['procesador']) 					$sql .= "AND c.procesador LIKE '%".trim(addEscapeosParaLike($params['procesador']))."%' ";
		if($params['id_tecnologia_central']) 		$sql .= "AND c.id_tecnologia_central = '".addslashes($params['id_tecnologia_central'])."' ";
		if($params['id_tecnologia_recolector']) 	$sql .= "AND c.id_tecnologia_recolector = '".addslashes($params['id_tecnologia_recolector'])."' ";
		if($params['id_tecnologia_enviador']) 		$sql .= "AND h.id_tecnologia_enviador = '".addslashes($params['id_tecnologia_enviador'])."' ";
		if($params['recoleccion']) 					$sql .= "AND r.nombre LIKE '%".trim(addEscapeosParaLike($params['recoleccion']))."%' ";
		if($params['actividad']) 					$sql .= "AND a.nombre LIKE '%".trim(addEscapeosParaLike($params['actividad']))."%' ";
		if($params['plantilla']) 					$sql .= "AND plantilla.nombre LIKE '%".trim(addEscapeosParaLike($params['plantilla']))."%' ";
								
		$sql .=	"GROUP BY r.id, r.nombre, r.habilitado";

		if($params['envio'] || $params['host']) return;
		
		$res = $entity->_db->leer($sql);
			
		return $this->_rs2Collection($res);		
	}
	
	function getRecoleccionesMonitoreo($nombreRecoleccion, $fechaDesde, $fechaHasta, $soloErroneas){						
		// SQL PARA RECOLECCIONES NO MANUALES
		
		$sql = "(SELECT ";
		$sql .=		"r.id as id_recoleccion, ";
		$sql .= 	"r.nombre as nombre_recoleccion, ";
		$sql .= 	"COUNT( DISTINCT c.id ) AS cantidad_centrales ";
		$sql .=	"FROM recoleccion r ";
		$sql .=		"INNER JOIN central c ON c.id_recoleccion = r.id ";
		$sql .=		"INNER JOIN recoleccion_ejecutada re ON r.id = re.id_recoleccion ";				
		
		if($soloErroneas==TRUE_) {
			$sql .=		"INNER JOIN tarea t ON  (t.id_central = c.id AND t.id_recoleccion_ejecutada = re.id)  ";									
		}
		
		$sql .= "WHERE r.baja_logica = '".FALSE_."' ";
		$sql .= 	"AND r.temporal = '".FALSE_."' ";
		$sql .= 	"AND c.baja_logica = '".FALSE_."' ";		
		$sql .= 	"AND re.baja_logica = '".FALSE_."' ";
		
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
			$sql .= "AND t.baja_logica = '".FALSE_."' ";
			$sql .= "AND EXISTS ( SELECT * FROM estado_tarea et WHERE et.id_tarea=t.id AND et.baja_logica='".FALSE_."' AND et.fallo='".TRUE_."' )";			 				
		}
		
		$sql .= " GROUP BY r.id, r.nombre) ";
		
		// FIN SQL PARA RECOLECCIONES NO MANUALES
				
		$sql .= " UNION ";
		
		// SQL PARA RECOLECCIONES MANUALES
		
		$sql .= "(SELECT ";
		$sql .=		"r.id as id_recoleccion, ";
		$sql .= 	"r.nombre as nombre_recoleccion, ";
		$sql .= 	"COUNT( DISTINCT c.id ) AS cantidad_centrales ";
		$sql .=	"FROM recoleccion r ";
		$sql .=		"INNER JOIN central_recoleccion_manual crm ON crm.id_recoleccion = r.id ";
		$sql .= 	"INNER JOIN central c ON crm.id_central = c.id ";
		$sql .=		"INNER JOIN recoleccion_ejecutada re ON r.id = re.id_recoleccion ";				
		
		if($soloErroneas==TRUE_) {
			$sql .=		"LEFT JOIN tarea t ON  (t.id_central = c.id AND t.id_recoleccion_ejecutada = re.id)  ";									
		}
		
		$sql .= "WHERE r.baja_logica = '".FALSE_."' ";
		$sql .= 	"AND r.temporal = '".FALSE_."' ";
		$sql .= 	"AND c.baja_logica = '".FALSE_."' ";		
		$sql .= 	"AND re.baja_logica = '".FALSE_."' ";
		
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
			$sql .= "AND t.baja_logica = '".FALSE_."' ";
			$sql .= "AND EXISTS ( SELECT * FROM estado_tarea et WHERE et.id_tarea=t.id AND et.baja_logica='".FALSE_."' AND et.fallo='".TRUE_."' )";			 				
		}
		
		$sql .= " GROUP BY r.id, r.nombre) ";
		
		// FIN SQL PARA RECOLECCIONES MANUALES
		
		$sql .= " ORDER BY nombre_recoleccion ";
						
		$res = $this->getEntity()->_db->leer($sql);
		return $this->_rs2Collection($res);
	}
}
?>