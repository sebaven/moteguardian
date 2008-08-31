<?php
include_once BASE_DIR."clases/negocio/clase.Envio.php";
include_once BASE_DIR."clases/negocio/clase.HostEnvio.php";
include_once BASE_DIR."clases/dao/dao.Planificacion.php";

class EnvioDAO extends AbstractDAO
{
	function getEntity()
	{
		return new Envio();
	}

	/**
	* Devuelve una sentencia SQL para realizar la busqueda de actividades de 
	* acuerdo a los a los nombre de centrales y a los nombre des actividades
	* 
	* @param $values (array asociativo) contiene como clave los campos a filtrar con los respectivos valores
	* @return (string) sentencia SQL
	*/
	function getSqlFindByParameters($values = '')
	{
		$w = array();
		
		$i=0;
		
		if ($values['nombre_envio']){			
			$w[$i] = "e.nombre LIKE '%".addEscapeosParaLike($values['nombre_envio']). "%'";
			$i++;
		}					
		if ($values['nombre_recoleccion']){			
			$w[$i] = "r.nombre LIKE '%".addEscapeosParaLike($values['nombre_recoleccion']). "%'";
			$i++;
		}			
		if ($values['nombre_central']){			
			$w[$i] = "c.nombre LIKE '%".addEscapeosParaLike($values['nombre_central']). "%'";
			$i++;
		}
		if ($values['nombre_host']){		
			$w[$i] = "h.nombre LIKE '%".addEscapeosParaLike($values['nombre_host']). "%'";
			$i++;
		}
		
		$sql  = "SELECT ";
		$sql .= 	"DISTINCT (e.id) as id, ";
		$sql .= 	"e.nombre as nombre_envio, ";
		$sql .= 	"r.nombre as nombre_recoleccion, ";
		$sql .= 	"e.habilitado as habilitado, ";
		$sql .= 	"e.inmediato as inmediato ";				
		$sql .=	"FROM envio e ";
		$sql .= 	"LEFT JOIN recoleccion r ON r.id = e.id_recoleccion ";
		$sql .= 	"LEFT JOIN central c ON c.id_recoleccion = r.id ";
		$sql .= 	"LEFT JOIN host_envio he ON he.id_envio = e.id ";
		$sql .= 	"LEFT JOIN host h ON h.id = he.id_host ";
		$sql .= "WHERE e.baja_logica = '".FALSE_."' ";
		$sql .= 	"AND e.temporal = '".FALSE_."' ";
		$sql .= 	"AND e.manual = '".FALSE_."' ";
		$sql .= 	"AND (r.baja_logica = '".FALSE_."' OR r.baja_logica IS NULL) ";
		$sql .= 	"AND (r.temporal = '".FALSE_."' OR r.temporal IS NULL) ";
		$sql .= 	"AND (r.manual = '".FALSE_."' OR r.manual IS NULL) ";
		$sql .= 	"AND (c.baja_logica = '".FALSE_."' OR c.baja_logica IS NULL) ";
		$sql .= 	"AND (h.baja_logica = '".FALSE_."' OR h.baja_logica IS NULL) ";

		if (count($w)>0)
			$sql .= " AND ".implode(' AND ', $w ) . " ";
			
		return $sql;
	}

	/**
	* Devuelve la cantidad de Envios para esa Actividad
	* @return (int) 
	* TODO: Este metodo no sirve! Verificar. Si no se usa en ningun lado, volarlo, sino corregirlo
	*/
	function getSqlCantEnvios($id)
	{			
		$sql  = "SELECT count(*) AS cantidad ";
		$sql .=	"FROM actividad a ";
		$sql .= "INNER JOIN actividad_envio ae ON a.id = ae.id_actividad ";
		$sql .= "WHERE a.id='".addslashes($id)."' ";
		$sql .= "AND ae.baja_logica = '".FALSE_."'";
		$sql .= "AND ae.temporal = '".FALSE_."'";

		// *** Realizar la consutla *** //
		$res = $this->getEntity()->_db->leer($sql);
		$a = $this->_rs2Array($res);
		return $a["cantidad"][0];
	}
	
	
	// Devuelve el sql para la estadistica de intentos de envio
	// Totaliza los intentos de envio, opcionalmente por fecha, tipo y tecnologia del enviador
	function getSqlEstadisticaIntentosEnvio($values = '') {
		$w = array();
		$i = 0;
		
		if($values['tipo_envio']) {
			switch ($values['tipo_envio']) {
				case "manual":
					 $w[$i] = "e.manual = '" .TRUE_."'";						
					 $i++;
					 break;
				
				case "automatica":
					 $w[$i] = "e.manual = '" .FALSE_."'";						
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
	
		if($values['id_tec_enviador']) {
			$w[$i] = "h.id_tecnologia_enviador = '" .$values['id_tec_enviador']."'";						
			$i++;
		}
			
		$sql  = 'SELECT count( distinct e.id ) AS CANTIDAD, te.nombre_tecnologia AS TECNOLOGIA, IF( e.manual, \'Manual\', \'Automatico\' ) AS TIPO, t.fecha_terminada AS FECHA ';
		$sql .= 'FROM tarea AS t ';
		$sql .= 'INNER JOIN recoleccion r ON r.id = t.id_recoleccion ';
		$sql .= 'INNER JOIN central c ON c.id = t.id_central ';
		$sql .= 'INNER JOIN envio e ON e.id_recoleccion = r.id ';
		$sql .= 'INNER JOIN host_envio he ON he.id_envio = e.id ';
		$sql .= 'INNER JOIN host h ON he.id_host = h.id ';
		$sql .= 'INNER JOIN tecnologia_enviador te ON h.id_tecnologia_enviador = te.id ';
		
		if ($w)
		{
			$sql .= "WHERE " . implode(' AND ', $w) . " ";
		}
		
		$sql  .= ' GROUP BY t.fecha_terminada, te.nombre_tecnologia, e.manual';
		
		return $sql;
	}
	
	
	
	/**
	* Obtengo los id de las actividades_envio y los id de las Tecnologias que tiene dicha Actividad
	* @return (int)
	* TODO: ESTO NO VA ACA! EL NOMBRE ES ASQUEROSO!! REFACTORIZAR URGENTE! 
	*/
	function getSqlIDAETec($id)
	{			
		$sql  = "SELECT ae.id, ae.id_tecnologia_enviador, ae.nombre, ae.inmediato, ae.habilitado ";
		$sql .=	"FROM actividad_envio ae ";
		$sql .= "INNER JOIN actividad a ON a.id = ae.id_actividad ";
		$sql .= "WHERE a.id='".addslashes($id)."' ";
		$sql .= "AND ae.baja_logica = '".FALSE_."' ";
		$sql .= "AND ae.temporal = '".FALSE_."'";		

		// *** Realizar la consutla *** //
		$res = $this->getEntity()->_db->leer($sql);
		$a = $this->_rs2Array($res);
		
		return $a;
	}
	
/**
	 * Devuelve un array con todos los objetos que cumplan con la condiciï¿½n que el campo $field contenga el valor
	 * dado en $value
	 * 
	 * @access public
	 * @param $field (string) nombre del campo a filtrar
	 * @param $value (string) valor que debe tener el campo
	 * @return array con los objetos que cumplen la condiciï¿½n
	 */ 
	function filterByField($field, $value)
	{
		$entity = $this->getEntity();
		
		// *** Armar la consulta *** //
		$sql  = " SELECT *";
		$sql .= " FROM $entity->_tablename ";
		$sql .= " WHERE $field = '".addslashes($value)."'";
		$sql .= " AND baja_logica ='".FALSE_."'";
		$sql .= " AND temporal ='".FALSE_."'";
		
		// *** Realizar la consutla *** //
		$res = $entity->_db->leer($sql);
		
		return $this->_rs2Collection($res);
	}
	
	/**
	 * Devuelve un array con la lista de todos los objetos de la clase definida en getEntity()
	 * 
	 * @access public
	 * @param $orderby (string) nombre del campo por el cual se van a ordenar los registros
	 * @return array contiene todos las entidades
	 */
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
		$sql .= " WHERER baja_logica='".FALSE_."'";
		
		
		if ($orderby)
			$sql .= "ORDER BY $orderby;";
		
		// *** Realizar la consutla y devolver el resultado *** //
		$res = $entity->_db->leer($sql);
		
		// *** Convertir el record set en un array asociativo y devolverlo *** 
		return $this->_rs2Collection($res);
	}
	
	
	function getNombreEnvioMenosElPasadoPorParametro($id_envio, $nombre_envio) {
		$entity = $this->getEntity();
		
		$sql = "SELECT nombre ";
		$sql .= "FROM ".$entity->_tablename." r ";
		$sql .= "WHERE r.id <> '".$id_envio."' ";
		$sql .= 	"AND r.baja_logica = '".FALSE_."' ";
		$sql .= 	"AND r.temporal = '".FALSE_."' ";
		$sql .= 	"AND r.nombre = '".addslashes(trim($nombre_envio))."' ";
		
		$res = $entity->_db->leer($sql);
		return $this->_rs2Collection($res);
	}

	
	function getSQLEnviosConFallas($params){
		$sql .= "SELECT ";
		$sql .= 	"e.id as id_envio, ";
		$sql .= 	"DISTINCT(t.id) as id, ";
		$sql .= 	"t.nombre as nombre, ";
		$sql .= 	"et.timestamp_ingreso as fecha_de_ejecucion, ";
		$sql .=		"CONCAT('<img onclick=\"popup(\'archivo\',\'acciones/tareas/descargar.php?archivo=../../batch/log/nucleo_srdf_',et.pid,'.log\',\'400\',\'400\')\" src=\"imagenes/b_search.png\" >') AS log ";
		$sql .= "FROM envio e ";
		$sql .=		"INNER JOIN informacion_estado ie ON e.id = ie.id_envio ";
		$sql .=		"INNER JOIN estado_tarea et ON et.id_informacion_estado = ie.id ";
		$sql .=		"INNER JOIN tarea t ON t.id = et.id_tarea ";
		$sql .= "WHERE t.esperando_envio = '".TRUE_."' ";
		$sql .= 	"AND et.fallo = '".TRUE_."' ";
		$sql .= 	"AND e.baja_logica = '".FALSE_."' ";
		$sql .=		"AND e.temporal = '".FALSE_."' ";
		$sql .=		"AND et.baja_logica = '".FALSE_."' ";
		$sql .= 	"AND t.baja_logica = '".FALSE_."' ";

		if($params['nombre']) $sql .= "AND e.nombre LIKE '%".addEscapeosParaLike($values['nombre']). "%' ";
		if($params['nombre_de_fichero']) $sql .= "AND t.nombre LIKE '%".addEscapeosParaLike($values['nombre_de_fichero']). "%' ";
		if($params['fecha_desde']) $sql.= "AND et.timestamp_ingreso >= '".$params['fecha_desde']." ".$params['id_horas_desde'].":".$params['id_minutos_desde'].":00' ";  
		if($params['fecha_hasta']) $sql.= "AND et.timestamp_ingreso <= '".$params['fecha_hasta']." ".$params['id_horas_hasta'].":".$params['id_minutos_hasta'].":00' ";  
		
		return $sql;
	}
	
	/*
	 * Devuelve un array con los envios que se deben lanzar entre esas 2 fechas, la 1ra exclusive y la 2da inclusive
	 */
	function filterByFecha($fechaDesde,$fechaHasta) {
		$planificacionDAO = new PlanificacionDAO();
		$planificaciones = $planificacionDAO->filterByFecha($fechaDesde,$fechaHasta);
		$enviosIds = array();
		$envios = array();
		foreach($planificaciones as $planificacion) {
			if(!(empty($planificacion->id_envio))) {
				// Planificacion de recoleccion
				if(!isset($enviosIds[$planificacion->id_envio])) {
					// Filtro por si se disparan por diversas planificaciones el mismo envío
					$envioIds[$planificacion->id_envio] = $planificacion->id_envio;
					$envio = new Envio();
					$envio->load($planificacion->id_envio);
					if(($envio->temporal == FALSE_)&&($envio->baja_logica == FALSE_)) {
						$envios[] = $envio;
					}
				}
			}
		}
		
		return $envios;
	}
	
	
	function getByIdRecoleccion($id_recoleccion){
		$entity = $this->getEntity();
		
		$sql = "SELECT ";
		$sql .= 	" id, ";
		$sql .= 	" nombre,  ";
		$sql .= 	" habilitado  ";
		$sql .= "FROM ".$entity->_tablename." ";
		$sql .= "WHERE baja_logica = '".FALSE_."'";
		$sql .= 	"AND id_recoleccion = '".addslashes($id_recoleccion)."' ";
		
		$res = $entity->_db->leer($sql);
		return $this->_rs2Collection($res);
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $idRecoleccion
	 * @return unknown
	 */
	function findInmediatosByIdRecoleccion($idRecoleccion) {
		$entity = $this->getEntity();
		
		// *** Armar la consulta *** //
		$sql = "SELECT * FROM envio ";
		$sql.= "WHERE baja_logica='".FALSE_."' ";
		$sql.= "AND temporal='".FALSE_."' ";
		$sql.= "AND id_recoleccion = '".addslashes($idRecoleccion)."' ";
		$sql.= "AND inmediato = '".TRUE_."'";
		
		// *** Realizar la consulta *** //
		$res = $entity->_db->leer($sql);
		
		$envios = $this->_rs2Collection($res);
		return $envios;
	}

	function getSQLSimularEstadoTareaEnviosPendientesFindByIdTarea($id_tarea){

		// SQL ENVIOS NO MANUALES 
		
		/**
		 * Explicación de la query:
		 * Dada una tarea se seleccionan todos los ie de envíos de la recolección de la tarea 
		 * para los cuales no existen estados tareas asociados.
		 * No se selecciona ninguno si la tarea tiene algún estado que falló (que no haya sido un envío)
		 */
		
		$sql = "(SELECT ";
		$sql .= 	" '-' AS nombre_actual, ";
		$sql .= 	" 'pendiente' AS timestamp_ingreso, ";
		$sql .= 	" NULL AS fallo, ";
		$sql .= 	" ie.nombre_estado AS nombre_estado ";
		$sql .= "FROM tarea t ";
		$sql .= 	"INNER JOIN envio e ON e.id_recoleccion = t.id_recoleccion ";
		$sql .= 	"INNER JOIN informacion_estado ie ON ie.id_envio = e.id ";			
		$sql .=	"WHERE t.id = '".$id_tarea."' ";
		$sql .= 	"AND e.baja_logica = '".FALSE_."' ";
		$sql .=		"AND e.temporal = '".FALSE_."' ";
		$sql .= 	"AND t.baja_logica = '".FALSE_."' ";
		$sql .= 	"AND NOT EXISTS ( ".
										"SELECT et.id ".
										"FROM estado_tarea et ".
											"INNER JOIN informacion_estado ie ON et.id_informacion_estado = ie.id ".
										"WHERE et.id_tarea = '".$id_tarea." '".
											"AND ie.id_envio = e.id ".
									" )";		
		$sql .= 	"AND NOT EXISTS (".
										"SELECT et.id ".
										"FROM estado_tarea et ".										
											"INNER JOIN informacion_estado ie ON et.id_informacion_estado = ie.id ".
										"WHERE et.id_tarea = '".$id_tarea." '".
											"AND et.fallo = '".TRUE_."' ".
											"AND ie.id_envio IS NULL ".		
									" ))";
		
		// FIN SQL ENVIOS NO MANUALES
		
		$sql .= " UNION ";
		
		// SQL ENVIOS MANUALES
		
		$sql .= "(SELECT ";
		$sql .= 	" '-' AS nombre_actual, ";
		$sql .= 	" 'pendiente' AS timestamp_ingreso, ";
		$sql .= 	" NULL AS fallo, ";
		$sql .= 	" ie.nombre_estado AS nombre_estado ";
		$sql .= "FROM tarea t ";
		$sql .= 	"INNER JOIN fichero_envio_manual fem ON fem.id_fichero = t.id ";
		$sql .=		"INNER JOIN envio e ON e.id = fem.id_envio ";
		$sql .= 	"INNER JOIN informacion_estado ie ON ie.id_envio = e.id ";			
		$sql .=	"WHERE t.id = '".$id_tarea."' ";
		$sql .= 	"AND e.baja_logica = '".FALSE_."' ";
		$sql .=		"AND e.temporal = '".FALSE_."' ";
		$sql .= 	"AND t.baja_logica = '".FALSE_."' ";
		$sql .= 	"AND NOT EXISTS ( ".
										"SELECT et.id ".
										"FROM estado_tarea et ".
											"INNER JOIN informacion_estado ie ON et.id_informacion_estado = ie.id ".
										"WHERE et.id_tarea = '".$id_tarea." '".
											"AND ie.id_envio = e.id".
									" ))";
		
		// FIN SQL ENVIOS MANUALES
		
		return $sql;
	}
}
?>
