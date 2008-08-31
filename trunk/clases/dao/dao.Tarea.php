<?php
include_once BASE_DIR."clases/negocio/clase.Tarea.php";
include_once BASE_DIR."clases/negocio/clase.EstadoTarea.php";
include_once BASE_DIR."clases/negocio/clase.InformacionEstado.php";

class TareaDAO extends AbstractDAO
{
	function getEntity()	
	{
		return new Tarea();
	}
	
	function getTareasByIdRecoleccionEjecutada($id_recoleccion_ejecutada, $soloErroneas = FALSE_)
	{
		$estadoTarea = new EstadoTarea();		
		$tarea = $this->getEntity();
		
		$sql = "SELECT ";
		$sql .= 		" id ";
		$sql .= "FROM ".$this->getEntity()->_tablename." t ";
		$sql .= "WHERE id_recoleccion_ejecutada = '".$id_recoleccion_ejecutada."' AND baja_logica = '".FALSE_."' ";
		if($soloErroneas==TRUE_) $sql .= "AND t.id in (SELECT t.id FROM ".$tarea->_tablename." t INNER JOIN ".$estadoTarea->_tablename." et ON et.id_tarea = t.id AND et.fallo='1' ) ";
			
		return $this->_rs2array($this->getEntity()->_db->leer($sql));
	}

	
	// Devuelve el SQL para generar el informe de monitoreo en tiempo
	// real de archivos pendientes de envio
	//
	// La query es identica a la del monitoreo de recolecciones, solo cambia el criterio de filtrado
	function getSqlMonitoreoEnvioTiempoReal($values = ' '){
		$w = array();
		$i = 0;
		
		/*	
		if($values['id_central']) {
			 $w[$i] = "t.id_central = '".$values['id_central']."'";						
		}*/
		$w[$i] = "t.pendiente_recoleccion = '".FALSE_."'";
	    $i++;
	    $w[$i] = "t.pendiente_envio = '".TRUE_."'";
	    $i++;

		$sql = 'SELECT * FROM tareas_pendientes t ';
		
		if ($w)
		{
			$sql .= "WHERE " . implode(' AND ', $w) . " ";
		}

		return $sql;
	}
	
	
	// Devuelve el SQL para generar el informe de monitoreo en tiempo
	// real de archivos pendientes de recoleccion 
	//
	// La query es identica a la del monitoreo de envios, solo cambia el criterio de filtrado
	function getSqlMonitoreoRecoleccionTiempoReal($values = ' '){
		$w = array();
		$i = 0;
		
		/*	
		if($values['id_central']) {
			 $w[$i] = "t.id_central = '".$values['id_central']."'";
			 i++;						
		}*/
		
	    $w[$i] = "t.pendiente_recoleccion = '".TRUE_."'";
	    $i++;

		$sql = 'SELECT * FROM tareas_pendientes t ';
		
		if ($w)
		{
			$sql .= "WHERE " . implode(' AND ', $w) . " ";
		}
		
		return $sql;
	}
	
	// Devuelve el SQL para obtener el total de ficheros recolectados y enviados
	// Cuenta tareas ejecutadas, filtrando opcionalmente por central, tecnologia, fecha desde y fecha hasta  
	function getSqlEstadisticaTotalFicherosEnviados($values = '') {
		$w = array();
		$i = 0;
		
		if($values['id_central']) {
			 $w[$i] = "t.id_central = '".$values['id_central']."'";						
		}

		if($values['fecha_desde']) {
			$desde = $values['fecha_desde']." 00:00";
			
			$w[$i] = "t.fecha_terminada >= '" .$desde."'";						
			$i++;
		}
		
		if($values['fecha_hasta']) {
			$hasta = $values['fecha_hasta']." 23:59";
			
			$w[$i] = "t.fecha_terminada <= '" .$hasta."'";						
			$i++;
		}
	
		if($values['id_tecnologia']) {
			$w[$i] = "c.id_tecnologia_central = '" .$values['id_tec_central']."'";						
			$i++;
		}
		if($values['id_destino']) {
			$w[$i] = "h.id = '" .$values['id_destino']."'";						
			$i++;
		}
		
			
		$sql  = 'SELECT count( distinct t.id ) AS CANTIDAD, tec.nombre AS TECNOLOGIA, c.nombre AS CENTRAL, h.nombre AS DESTINO, t.fecha_terminada AS FECHA ';
		$sql .= 'FROM tarea AS t ';
		$sql .= 'INNER JOIN central c ON c.id = t.id_central ';
		$sql .= 'INNER JOIN tecnologia_central tec ON tec.id = c.id_tecnologia_central ';
		$sql .= 'INNER JOIN recoleccion r ON r.id = t.id_recoleccion ';
		$sql .= 'INNER JOIN envio e ON e.id_recoleccion = r.id ';
		$sql .= 'INNER JOIN host_envio he ON he.id_envio = e.id ';
		$sql .= 'INNER JOIN host h ON h.id = he.id_host ';
		
		if ($w)
		{
			$sql .= "WHERE " . implode(' AND ', $w) . " ";
		}
		
		$sql  .= ' GROUP BY t.fecha_terminada, c.id, tec.id, h.id';
		
		return $sql;
	}
	
	function getSqlEstadoTareas($id_tarea = '')
	{			
		$w = array();
		
		if ($id_tarea) {
			$w[] = "et.id_tarea =" . addslashes($id_tarea);						
		}
			
		$sql  = "(SELECT ";
		$sql .= 	"et.nombre_actual AS nombre_actual,  ";
		$sql .=		"et.timestamp_ingreso AS timestamp_ingreso, "; 
		$sql .= 	"et.fallo AS fallo, "; 
        $sql .= 	"CONCAT('<a onclick=\"popup(\'archivo\',\'acciones/tareas/descargar.php?archivo=../../batch/log/nucleo_srdf_',et.pid,'.log\',\'400\',\'400\')\" >',ie.nombre_estado,'</a>') AS nombre_estado ";
		$sql .=	"FROM estado_tarea et ";
		$sql .= 	"INNER JOIN informacion_estado ie ON et.id_informacion_estado = ie.id ";				
		$sql .= "WHERE et.baja_logica = '".FALSE_."' ";
						
		if ($w)	{
			$sql .= " AND ".implode(' AND ', $w) . " ";
		}
	
		$envioDAO = new EnvioDAO();		
		$sql .= ") UNION ".$envioDAO->getSQLSimularEstadoTareaEnviosPendientesFindByIdTarea(addslashes($id_tarea));
			
		return $sql;
	}	
	
	function archivoYaRecolectado($id_recoleccion,$ubicacion,$nombre) {
		$sql = "SELECT * FROM tarea WHERE id_recoleccion = '".addslashes($id_recoleccion)."' ";
		$sql.= "AND nombre_original = '".addslashes($nombre)."' AND ubicacion_original = '".addslashes($ubicacion)."' ";
		
		$entity = $this->getEntity();
		$res = $entity->_db->leer($sql);
		
		if(!isset($res)) return false;
		
		if($entity->_db->numero_filas($res)) return true;
		else return false;
	}
	
	
	/**
	 * CUIDADO, NO DEVUELVE TAREAS, NI OBJECTOS, SINO UN ARRAY CON LOS DATOS DEL ARCHIVO PARA ENVIARLO
	 */
	function getArchivosAEnviar($id_recoleccion,$id_envio) {
		// Obtengo las tareas de la recolección que están como pendientes de envío
		$sql = "SELECT t.id FROM tarea t ";
		$sql.= "WHERE t.id_recoleccion = '".addslashes($id_recoleccion)."' AND t.esperando_envio = '".TRUE_."'";		

		$entity = $this->getEntity();
		$db = $entity->_db;
		$res = $db->leer($sql);
		
		$archivos = array();
		
		if((!isset($res))||($db->numero_filas($res) == 0)) {
			return $archivos;
		}
		
		// Iterar por cada uno de las tareas
		while($r = $db->reg_array($res)) {
			$tarea = new Tarea($r['id']);
			$estadoTarea = new EstadoTarea();
			$informacionEstado = new InformacionEstado();
			
			if(($tarea->esperando_envio == TRUE_) || ($tarea->fecha_terminada)) {			
				// sub subselect que obtiene el maximo id de los estados de tarea que no corresponden a envios 
				$sqlsubselect = "SELECT ";
				$sqlsubselect .= "MAX(et.id) ";
				$sqlsubselect .= "FROM ".$estadoTarea->_tablename." et ";			
				$sqlsubselect .= "INNER JOIN ".$informacionEstado->_tablename." ie ON ie.id = et.id_informacion_estado ";
				$sqlsubselect .= "WHERE ie.id_envio IS NULL ";
				$sqlsubselect .= "AND et.id_tarea = '".$r['id']."' ";
			
				// 	Obtiene la ubicacion de la tarea que tiene el id obtenido en la consulta anterior			
					
				$sql = 	"SELECT et.ubicacion, et.nombre_actual, et.nombre_original, et.ubicacion ";
				$sql .= "FROM ".$estadoTarea->_tablename." et ";
				$sql .= "WHERE et.id IN ($sqlsubselect)";			
			
				
				$rs = $tarea->_db->leer($sql);	
								
			} else {
				continue;
			}

			if(!isset($rs)||($db->numero_filas($rs) == 0)) continue;
	
			$estadoTarea = $this->getEntity()->_db->reg_array($rs);
			$archivos[] = array('archivoOriginal'=>$estadoTarea['nombre_original'],
								'archivoTemporal'=>$estadoTarea['nombre_actual'],
								'ubicacionOriginal'=>"",
								'ubicacionTemporal'=>$estadoTarea['ubicacion'],
								'idTarea'=> $r['id']
								);
		}
		
		return $archivos;
	}

	/**
	 * CUIDADO, NO DEVUELVE TAREAS, NI OBJECTOS, SINO UN ARRAY CON LOS DATOS DEL ARCHIVO PARA ENVIARLO
	 */
	function getArchivosAEnviarManualmente($id_envio) {
		$sql = "SELECT *,t.id AS t_id FROM tarea t ";
		$sql.= "JOIN fichero_envio_manual fem ON t.id = fem.id_fichero ";
		$sql.= "WHERE fem.id_envio = '" .$id_envio."' ";

		$entity = $this->getEntity();
		$db = $entity->_db;
		$res = $db->leer($sql);
		$archivos = array();

		if((!isset($res))||($db->numero_filas($res) == 0)) {
			return $archivos;
		}
		// Iterar por cada uno de las tareas
		
		while($r = $db->reg_array($res))
		{
			// Esto estaba para no enviar archivos ya enviados...pero aca
			// no aplica
			
//			// Busco que haya registro de alguna vez haberse enviado bien
//			$sql = " SELECT * FROM estado_tarea et ";
//			$sql.= " JOIN informacion_estado ie ON ie.id = et.id_informacion_estado ";
//			$sql.= " WHERE et.fallo = '".FALSE_."' AND et.id_tarea = '".$r['t_id']."' ";
//			$rs = $db->leer($sql);
//			if(!isset($rs)) continue;
//			if($db->numero_filas($rs) > 0) {
//				// Ya se mando, ok
//				continue;
//			}
			
			// Ahora busco los datos para la recoleccion
			$sql = " SELECT * FROM estado_tarea WHERE id_tarea = '".$r['t_id']."' ";
			$rs = $db->leer($sql);
			if((!isset($rs))||($db->numero_filas($rs) == 0)) {
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
	
	function FilterByPendientesPorRecoleccion($id_recoleccion) {
		$sql = "SELECT t.* FROM tarea t JOIN recoleccion_ejecutada re ON t.id_recoleccion_ejecutada = re.id ";
		$sql.= "WHERE re.id_recoleccion = '".$id_recoleccion."' AND t.esperando_recoleccion = '".TRUE_."'";
		
		$entity = $this->getEntity();
		
		$res = $entity->_db->leer($sql);
		if(!isset($res)) {
			return array();
		} else return $this->_rs2Collection($res);
	}
	

	function getPathArchivoValidado($id) {
		$tarea = new Tarea($id);
		$estadoTarea = new EstadoTarea();
		$informacionEstado = new InformacionEstado();
						
		if($tarea->esperando_envio || $tarea->fecha_terminada ) {
			
			// sub subselect que obtiene el mÃ¡ximo id de los estados de tarea que no corresponden a envios 
			
			$sqlsubselect = "SELECT ";
			$sqlsubselect .= 	"MAX(et.id) ";
			$sqlsubselect .= "FROM ".$estadoTarea->_tablename." et ";			
			$sqlsubselect .= 	"INNER JOIN ".$informacionEstado->_tablename." ie ON ie.id = et.id_informacion_estado ";
			$sqlsubselect .= "WHERE ie.id_envio IS NULL ";
			$sqlsubselect .= 	"AND et.id_tarea = '".$id."' ";
			
			// Obtiene la ubicaciÃ³n de la tarea que tiene el id obtenido en la consulta anterior			
			
			$sql = 	"SELECT CONCAT(et.ubicacion,et.nombre_actual) as ubicacion ";
			$sql .= "FROM ".$estadoTarea->_tablename." et ";
			$sql .= "WHERE et.id IN ($sqlsubselect)";			
			
			$resultado = $tarea->_rs2array($tarea->_db->leer($sql));
		
			return $resultado['ubicacion'][0];
		} else {
			return 'noValidado';
		}		
	}
	
	
	/**
	* Devuelve una sentencia SQL para realizar la busqueda de ficheros de 
	* acuerdo a los filtros especificados
	* 
	* @param $values (array asociativo) contiene como clave los campos a filtrar con los respectivos valores
	* @return (string) sentencia SQL
	*/
	function getSqlFindByParameters($values = '')
	{
		$w = array();
		$w[0] = ( "t.baja_logica= '".FALSE_."'" );
		
		$i = 1;
		
		if ($values['nombre_fichero']) {	
			$nombre_orig_fichero = addEscapeosParaLike($values['nombre_fichero']);
			$w[$i] = "t.nombre_original LIKE '%" . addslashes($nombre_orig_fichero) . "%'";
			$i++;
		}
		
		if ($values['nombre_central']) {	
			$nombre_central = addEscapeosParaLike($values['nombre_central']);
			$w[$i] = "c.nombre LIKE '%" . addslashes($nombre_central) . "%'";
			$i++;
		}
		
		if ($values['procesador']) {	
			$w[$i] = "c.procesador = " . addslashes($values['procesador']) . "'";
			$i++;
		}
							
		if ($values['id_tecnologia_recolector']) {
			$w[$i] = "id_tecnologia_recolector = '" . addslashes($values['id_tecnologia_recolector']) . "'";
			$i++;
		}
			
		if ($values['id_tecnologia_central']) {
			$w[$i] = "id_tecnologia_central = '" . addslashes($values['id_tecnologia_central']) . "'";
			$i++;
		}

		if($values['id_envio_manual']) {
			if($values['id_envio_manual']!='NULL') {
				$w[$i] = "t.id NOT IN (SELECT DISTINCT femm.id_fichero FROM fichero_envio_manual femm WHERE femm.id_envio = '". addslashes($values['id_envio_manual']) ."') ";
			}			
			$i++;
		}
		
		$sql  = "SELECT DISTINCT t.id, "; 
		$sql .= "		t.nombre_original, ";
		$sql .= "		c.nombre AS nombre_central, ";
		$sql .= "		c.procesador, ";
		$sql .= "		r.nombre AS nombre_recolector, ";
		$sql .=	"		tecnologia_recolector.nombre_tecnologia, ";
		$sql .= "		CONCAT(tecnologia_central.nombre, ' v',tecnologia_central.version) AS nombre_tecnologia_central ";
		$sql .=	"FROM tarea t ";
		$sql .= "LEFT JOIN recoleccion r ON r.id = t.id_recoleccion ";
		$sql .= "LEFT JOIN central c ON c.id_recoleccion = t.id_recoleccion ";
		$sql .= "INNER JOIN tecnologia_recolector ON tecnologia_recolector.id = c.id_tecnologia_recolector ";
		$sql .= "INNER JOIN tecnologia_central ON tecnologia_central.id = c.id_tecnologia_central ";
		$sql .= "LEFT JOIN fichero_envio_manual fem ON fem.id_fichero = t.id ";
		
		if ($w)
		{
			$sql .= "WHERE " . implode(' AND ', $w) . " ";
		}

		return $sql;
	}
	
	
	function getSqlFindAsociadasEnvioManualByParameters($values = '') {
		
		$w = array();
		$w[0] = ( "t.baja_logica= '".FALSE_."'" );
		$w[1] = ( "r.baja_logica= '".FALSE_."'" );
		$w[2] = ( "c.baja_logica= '".FALSE_."'" );
		
		$i = 3;

		if($values['id_envio_manual']) {
			if($values['id_envio_manual']!='NULL') {
				$w[$i] = "fem.id_envio = '" . addslashes($values['id_envio_manual']) . "' ";;
			}			
			$i++;
		}
		
		$sql  = "SELECT DISTINCT t.id, "; 
		$sql .= "		t.nombre_original, ";
		$sql .= "		c.nombre AS nombre_central, ";
		$sql .= "		c.procesador, ";
		$sql .= "		r.nombre AS nombre_recolector, ";
		$sql .=	"		tecnologia_recolector.nombre_tecnologia, ";
		$sql .= "		CONCAT(tecnologia_central.nombre, ' v',tecnologia_central.version) AS nombre_tecnologia_central ";
		$sql .=	"FROM tarea t ";
		$sql .= "LEFT JOIN central c ON c.id = t.id_central ";
		$sql .= "LEFT JOIN recoleccion r ON c.id_recoleccion = r.id ";
		$sql .= "INNER JOIN tecnologia_recolector ON tecnologia_recolector.id = c.id_tecnologia_recolector ";
		$sql .= "INNER JOIN tecnologia_central ON tecnologia_central.id = c.id_tecnologia_central ";
		$sql .= "LEFT JOIN fichero_envio_manual fem ON fem.id_fichero = t.id ";
		
		if ($w)
		{
			$sql .= "WHERE " . implode(' AND ', $w) . " ";
		}

		return $sql;
	}
	
	
	/**
	* Devuelve una sentencia SQL para realizar la busqueda de ficheros de 
	* acuerdo a los filtros especificados
	* 
	* @param $values (array asociativo) contiene como clave los campos a filtrar con los respectivos valores
	* @return (string) sentencia SQL
	*/
	function getSqlFindFicherosParaSeleccionarByParameters($values = '')
	{
		$w = array();
		$w[0] = ( "t.baja_logica= '".FALSE_."'" );
		$w[1] = ( "r.baja_logica= '".FALSE_."'" );
		$w[2] = ( "c.baja_logica= '".FALSE_."'" );
		$w[3] = ( "e.baja_logica= '".FALSE_."'" );
		//$w[4] = ( "p.baja_logica= '".FALSE_."'" );
		
		$i = 4;
		
		if ($values['nombre_original']) {	
			$w[$i] = "t.nombre_original LIKE '%" . addEscapeosParaLike($values['nombre_original']) . "%'";
			$i++;
		}
		
		if ($values['nombre_central']) {	
			$w[$i] = "c.nombre LIKE '%" . addEscapeosParaLike($values['nombre_central']) . "%'";
			$i++;
		}
		
		if ($values['procesador']) {	
			$w[$i] = "c.procesador = '" . addslashes($values['procesador']) . "'";
			$i++;
		}

		if ($values['nombre_envio']) {	
			$w[$i] = "e.nombre LIKE '%" . addEscapeosParaLike($values['nombre_envio']) . "%'";
			$i++;
		}
		
		if ($values['fecha_desde']) {
			$w[$i] = "p.fecha_vigencia >= '" . addslashes($values['fecha_desde']) . "'";
			$i++;
		}
		
		if ($values['fecha_hasta']) {
			$w[$i] = "p.fecha_vigencia <= '" . addslashes($values['fecha_hasta']) . "'";
			$i++;
		}
		
		if ($values['tarea_desde']) {
			$w[$i] = "t.id >= '" . addslashes($values['tarea_desde']) . "'";
			$i++;
		}
		
		if ($values['tarea_hasta']) {
			$w[$i] = "t.id <= '" . addslashes($values['tarea_hasta']) . "'";
			$i++;
		}
							
		if ($values['id_tecnologia_recolector']) {
			$w[$i] = "id_tecnologia_recolector = '" . addslashes($values['id_tecnologia_recolector']) . "'";
			$i++;
		}
			
		if ($values['id_tecnologia_central']) {
			$w[$i] = "id_tecnologia_central = '" . addslashes($values['id_tecnologia_central']) . "'";
			$i++;
		}

		if($values['id_envio_manual']) {
			if($values['id_envio_manual']!='NULL') {
				$w[$i] = "t.id NOT IN (SELECT DISTINCT femm.id_fichero FROM fichero_envio_manual femm WHERE femm.id_envio = '". addslashes($values['id_envio_manual']) ."') ";
			}			
			$i++;
		}
		
		$sql  = "SELECT DISTINCT (t.id) as id, "; 
		$sql .= "		t.nombre_original, ";
		$sql .= "		c.nombre AS nombre_central, ";
		$sql .= "		c.procesador, ";
		$sql .= "		r.nombre AS nombre_recolector, ";
		$sql .=	"		tecnologia_recolector.nombre_tecnologia, ";
		$sql .= "		CONCAT(tecnologia_central.nombre, ' v',tecnologia_central.version) AS nombre_tecnologia_central ";
		$sql .=	"FROM tarea t ";
		$sql .= "LEFT JOIN central c ON c.id = t.id_central ";
		$sql .= "LEFT JOIN recoleccion r ON c.id_recoleccion = r.id ";
		$sql .= "LEFT JOIN tecnologia_recolector ON tecnologia_recolector.id = c.id_tecnologia_recolector ";
		$sql .= "LEFT JOIN tecnologia_central ON tecnologia_central.id = c.id_tecnologia_central ";
		$sql .= "LEFT JOIN fichero_envio_manual fem ON fem.id_fichero = t.id ";
		$sql .= "LEFT JOIN planificacion p ON p.id_envio = fem.id_envio ";
		$sql .= "LEFT JOIN envio e ON e.id_recoleccion = t.id_recoleccion ";
		
		if ($w)
		{
			$sql .= "WHERE " . implode(' AND ', $w) . " ";
		}

		return $sql;	
	}
	
	function getAllIds(){
		$entity = $this->getEntity();
		
		$sql = "SELECT id FROM ".$entity->_tablename." WHERE baja_logica = '".FALSE_."' ";

		$res = $entity->_db->leer($sql);
		return $this->_rs2array($res);
	}
	
	function getIdsNoAsignadasEnvioManual($idEnvioManual){
		$entity = $this->getEntity();
		
		$sql = 	"SELECT DISTINCT t.id FROM ".$entity->_tablename." t";
		$sql .= " LEFT JOIN fichero_envio_manual fem ON fem.id_fichero = t.id ";
		$sql .= " WHERE t.id NOT IN (SELECT fem.id_fichero FROM fichero_envio_manual fem";
		$sql .= " WHERE fem.id_envio = '". addslashes($idEnvioManual) ."') ";
		
		$res = $entity->_db->leer($sql);
		return $this->_rs2array($res);
	}
	
	function getIdsAsignadasEnvioManual($idEnvioManual){
		$entity = $this->getEntity();
		
		$sql = 	"SELECT DISTINCT t.id FROM ".$entity->_tablename." t";
		$sql .= " INNER JOIN fichero_envio_manual fem ON fem.id_fichero = t.id ";
		$sql .= " WHERE fem.id_envio = '". addslashes($idEnvioManual) ."' ";
		
		$res = $entity->_db->leer($sql);
		return $this->_rs2array($res);
	}

	function getTareasMonitoreo($idEjecucion, $idCentral, $soloErroneas) {
		$ejecucion = new RecoleccionEjecutada($idEjecucion);
		$recoleccion = new Recoleccion($ejecucion->id_recoleccion);
		
		$sql = "SELECT ";
		$sql .=		"t.id as id_tarea, ";
		$sql .= 	"t.nombre_original as nombre_original ";
		$sql .=	"FROM recoleccion_ejecutada re ";
		
		if($recoleccion->manual==FALSE_) {			
			$sql .=	"INNER JOIN central c ON c.id_recoleccion = re.id_recoleccion ";
		} else {
			$sql .=	"INNER JOIN central_recoleccion_manual crm ON crm.id_recoleccion = re.id_recoleccion ";
			$sql .=	"INNER JOIN central c ON crm.id_central = c.id ";
		}		
							
		$sql .=		"INNER JOIN tarea t ON (t.id_central = c.id AND t.id_recoleccion_ejecutada = re.id) ";			
		$sql .= "WHERE c.id = '".$idCentral."' ";		
		$sql .= 	"AND re.id = '".$idEjecucion."' ";
		$sql .= 	"AND t.baja_logica = '".FALSE_."' ";

		if($soloErroneas==TRUE_) {
			$sql .= "AND EXISTS ( SELECT * FROM estado_tarea et WHERE et.id_tarea=t.id AND et.baja_logica='".FALSE_."' AND et.fallo='".TRUE_."' )";			 				
		}
						
		$res = $this->getEntity()->_db->leer($sql);
		return $this->_rs2Collection($res);	
	}
}
?>
