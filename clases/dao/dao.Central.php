<?php
include_once BASE_DIR."clases/negocio/clase.Central.php";
include_once BASE_DIR."clases/negocio/clase.Recoleccion.php";

/**
 * @author cgalli
 *
 */
class CentralDAO extends AbstractDAO
{
	function getEntity()
	{
		return new Central();
	}

	
	/**
	 * Esta funci�n devuelve una central correspondiente al nombre pasado por parametro
	 * (El nombre es unico)
	 *
	 * @param unknown_type $nombre
	 * @return unknown
	 */
	function getByNombre($nombre = "") {
		$array = $this->filterByField("nombre",$nombre);
		return $array[0];
	}

	function getIdsCentralesNoAsignadas(){
		$entity = $this->getEntity();
		
		$sql = "SELECT id FROM ".$entity->_tablename." WHERE ( " .
																" id_recoleccion IS NULL ". 
																	" OR ".
																" id_recoleccion IN ". 
																	" ( ".
																		" SELECT r2.id ". 
																		" FROM recoleccion r2 ". 
																		" WHERE ". 
																			" r2.baja_logica = '".TRUE_."' OR ". 
																			" ( r2.temporal = '".TRUE_."' AND r2.id <> '".addslashes($values['id_recoleccion_que_esta_siendo_configurada'])."' ) ".
																	" ) ".
															" ) AND baja_logica = '".FALSE_."'";
		$res = $entity->_db->leer($sql);
		return $this->_rs2Collection($res);
	}
	
	function getAllIds(){
		$entity = $this->getEntity();
		
		$sql = "SELECT id FROM ".$entity->_tablename." WHERE baja_logica = '".FALSE_."' ";

		$res = $entity->_db->leer($sql);
		return $this->_rs2array($res);
	}
	
	function getIdsNoAsignadasRecoleccionManual($idRecoleccionManual){
		$entity = $this->getEntity();
		
		$sql = 	"SELECT DISTINCT c.id FROM ".$entity->_tablename." c";
		$sql .= " LEFT JOIN central_recoleccion_manual crm ON crm.id_central = c.id ";
		$sql .= " WHERE c.id NOT IN (SELECT crm.id_central FROM central_recoleccion_manual crm";
		$sql .= " WHERE crm.id_recoleccion = '". addslashes($idRecoleccionManual) ."') ";
		
		$res = $entity->_db->leer($sql);
		return $this->_rs2array($res);
	}
	
	function getIdsAsignadasRecoleccionManual($idRecoleccionManual){
		$entity = $this->getEntity();
		
		$sql = 	"SELECT DISTINCT c.id FROM ".$entity->_tablename." c";
		$sql .= " INNER JOIN central_recoleccion_manual crm ON crm.id_central = c.id ";
		$sql .= " WHERE crm.id_recoleccion = '". addslashes($idRecoleccionManual) ."' ";
		
		$res = $entity->_db->leer($sql);
		return $this->_rs2array($res);
	}
	
	function getAsignadasRecoleccionManual($idRecoleccionManual){
		$entity = $this->getEntity();
		
		$sql = 	"SELECT DISTINCT c.* FROM ".$entity->_tablename." c";
		$sql .= " INNER JOIN central_recoleccion_manual crm ON crm.id_central = c.id ";
		$sql .= " WHERE crm.id_recoleccion = '". addslashes($idRecoleccionManual) ."' ";
		
		$res = $entity->_db->leer($sql);
		return $this->_rs2Collection($res);
	}
	
	function getCircuitosIncompletosCentralesNoAsignadas($params) {
		$entity = $this->getEntity();
		
		$sql = 	"SELECT ";		
		$sql .=		"c.nombre, ";
		$sql .=		"c.procesador ";							 	
		$sql .= "FROM ".$entity->_tablename." c ";		
		$sql .= "WHERE c.baja_logica = '".FALSE_."' ";
		$sql .= 	"AND (c.id_recoleccion IS NULL OR c.id_recoleccion IN ( SELECT r2.id FROM recoleccion r2 WHERE r2.baja_logica = '".TRUE_."' OR r2.temporal = '".TRUE_."' )) ";		
		
		if($params['central']) 						$sql .= "AND c.nombre LIKE '%".trim(addEscapeosParaLike($params['central']))."%' ";
		if($params['procesador']) 					$sql .= "AND c.procesador LIKE '%".trim(addEscapeosParaLike($params['procesador']))."%' ";
		if($params['id_tecnologia_central']) 		$sql .= "AND c.id_tecnologia_central = '".addslashes($params['id_tecnologia_central'])."' ";
		if($params['id_tecnologia_recolector']) 	$sql .= "AND c.id_tecnologia_recolector = '".addslashes($params['id_tecnologia_recolector'])."' ";
				
		if($params['recoleccion'] || $params['actividad'] || $params['plantilla'] || $params['envio'] || $params['host'] || $params['id_tecnologia_enviador']) return;
						
		$res = $entity->_db->leer($sql);
	
		return $this->_rs2Collection($res);		
	}
	
	 /**
	 * Esta funci�n devuelve una central correspondiente al procesador pasado por parametro
	 * (El procesador es unico)
	 *
	 * @param unknown_type $procesador
	 * @return unknown
	 */
	function getByProcesador($procesador = "") {				
		$array = $this->filterByField("procesador",$procesador);
		return $array[0];
	}
	
	/**
	* Devuelve una sentencia SQL para realizar la busqueda de centrales de 
	* acuerdo a los filtros especificados
	* 
	* @param $values (array asociativo) contiene como clave los campos a filtrar con los respectivos valores
	* @return (string) sentencia SQL
	*/
	function getSqlFindByParameters($values = '') {
		$w = array();
		$w[0] = ( "c.baja_logica = '".FALSE_."'" );
		
		$i = 1;
		if ($values['nombre']) {	
			$w[$i] = "c.nombre LIKE '%" . addEscapeosParaLike($values['nombre']) . "%'";
			$i++;
		}			
		if ($values['procesador']) {			
			$w[$i] = "procesador LIKE '%" . addEscapeosParaLike($values['procesador']) . "%'";
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
		if($values['id_recoleccion']) {
			if($values['id_recoleccion']=='NULL') {
				$w[$i] = "c.id_recoleccion IS NULL OR c.id_recoleccion IN (SELECT id FROM recoleccion WHERE baja_logica = '".TRUE_."' )";
			} else {
				$w[$i] = "c.id_recoleccion = '" . addslashes($values['id_recoleccion']) ."'";	
			}			
			$i++;
		}
		if($values['id_recoleccion_manual']) {
			if($values['id_recoleccion_manual']!='NULL') {
				$w[$i] = "c.id NOT IN (SELECT crmm.id_central FROM central_recoleccion_manual crmm WHERE crmm.id_recoleccion = '". addslashes($values['id_recoleccion_manual']) ."') ";
			}			
			$i++;
		}
		
		$sql  = "SELECT DISTINCT c.id, "; 
		$sql .= "		c.nombre AS nombre_central, ";
		$sql .= "		c.procesador, ";
		$sql .= "		c.descripcion as descripcion_central, ";
		$sql .=	"		tecnologia_recolector.nombre_tecnologia, "; 
		$sql .= "		tecnologia_recolector.accion, ";
		$sql .= "		CONCAT(tecnologia_central.nombre, ' v',tecnologia_central.version) AS nombre_tecnologia_central ";
		$sql .=	"FROM central c ";
		$sql .= "INNER JOIN tecnologia_recolector ON tecnologia_recolector.id = c.id_tecnologia_recolector ";
		$sql .= "INNER JOIN tecnologia_central ON tecnologia_central.id = c.id_tecnologia_central ";
		$sql .= "LEFT JOIN central_recoleccion_manual crm ON crm.id_central = c.id ";
		
		if ($w)
		{
			$sql .= "WHERE " . implode(' AND ', $w) . " ";
		}

		return $sql;
	}

	// TODO: eliminar este y usar solo findByIdRecoleccion
	function getCentralesByRecoleccion($id_recoleccion) {
		$entity = $this->getEntity();
		$recoleccion = new Recoleccion();
		
		$sql = "SELECT ";
		$sql .= 	"c.id, c.nombre, c.procesador ";
		$sql .= "FROM ".$entity->_tablename." c ";
		$sql .=		"INNER JOIN ".$recoleccion->_tablename." r ON c.id_recoleccion = r.id ";
		$sql .=	"WHERE ";
		$sql .= 	"r.id = '".addslashes($id_recoleccion)."' ";
		$sql .= 	" AND c.baja_logica = '".FALSE_."' ";

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
		$sql .= " WHERE baja_logica='".FALSE_."'";
		
		if ($orderby)
			$sql .= "ORDER BY $orderby;";
		
		// *** Realizar la consutla y devolver el resultado *** //
		$res = $entity->_db->leer($sql);
		
		// *** Convertir el record set en un array asociativo y devolverlo *** 
		return $this->_rs2Collection($res);
	}

	/** Devuelve un array con todos los objetos que cumplan con la condici�n que el campo $field contenga el valor
	 * dado en $value
	 * 
	 * @access public
	 * @param $field (string) nombre del campo a filtrar
	 * @param $value (string) valor que debe tener el campo
	 * @return array con los objetos que cumplen la condici�n
	 */ 
	function filterByField($field, $value)	
	{
		$entity = $this->getEntity();
		
		// *** Armar la consulta *** //
		$sql  = " SELECT *";
		$sql .= " FROM ".$entity->_tablename;
		$sql .= " WHERE ".$field." = '".addslashes($value)."'";
		$sql .= " AND baja_logica='".FALSE_."'";
		
		// *** Realizar la consutla *** //
		$res = $entity->_db->leer($sql);
		
		return $this->_rs2Collection($res);
	}	
	
	/**
	 * Devuelve un array con todas las centrales asociadas a la recolección
	 * cuyo id se especifica en el parametro $idRecoleccion
	 *
	 * @param $idRecoleccion
	 */
	function findByIdRecoleccion($idRecoleccion) {
		return $this->filterByField("id_recoleccion",$idRecoleccion);
	}
	
	
	// TODO: Ver si esto se usa
/**
	* Devuelve una sentencia SQL para realizar la busqueda de centrales de 
	* acuerdo a los filtros especificados
	* 
	* @param $values (array asociativo) contiene como clave los campos a filtrar con los respectivos valores
	* @return (string) sentencia SQL
	*/
	function getSqlFindAsociadasRecoleccionManualByParameters($values = '')
	{
		$w = array();
		$w[0] = ( "c.baja_logica= '".FALSE_."'" );
		
		$i = 1;
			
		if($values['id_recoleccion_manual']) {
			if($values['id_recoleccion_manual']=='NULL') {
				$w[$i] = "crm.id_recoleccion IS NULL ";
			} else {
				$w[$i] = "crm.id_recoleccion = '" . addslashes($values['id_recoleccion_manual']) ."'";	
			}			
			$i++;
		}
			
		$sql  = "SELECT DISTINCT c.id, "; 
		$sql .= "		c.nombre AS nombre_central, ";
		$sql .= "		c.procesador, ";
		$sql .= "		c.descripcion as descripcion_central, ";
		$sql .=	"		tecnologia_recolector.nombre_tecnologia, "; 
		$sql .= "		tecnologia_recolector.accion, ";
		$sql .= "		CONCAT(tecnologia_central.nombre, ' v',tecnologia_central.version) AS nombre_tecnologia_central ";
		$sql .=	"FROM central c ";
		$sql .= "INNER JOIN tecnologia_recolector ON tecnologia_recolector.id = c.id_tecnologia_recolector ";
		$sql .= "INNER JOIN tecnologia_central ON tecnologia_central.id = c.id_tecnologia_central ";
		$sql .= "LEFT JOIN central_recoleccion_manual crm ON crm.id_central = c.id ";
		if ($w)
		{
			$sql .= "WHERE " . implode(' AND ', $w) . " ";
		}

		return $sql;
	}
		
	
/**
	* Devuelve una sentencia SQL para realizar la busqueda de centrales de 
	* acuerdo a los filtros especificados
	* 
	* @param $values (array asociativo) contiene como clave los campos a filtrar con los respectivos valores
	* @return (string) sentencia SQL
	*/
	function getSqlFindCentralesParaSeleccionarByParameters($values = '') {		
		$w = array();
		$w[0] = ( "c.baja_logica= '".FALSE_."'" );		
		$i = 1;
		
		if ($values['nombre']) {	
			$w[$i] = "c.nombre LIKE '%" . addEscapeosParaLike($values['nombre']) . "%'";
			$i++;
		}
		if ($values['nombre_recoleccion']) {	
			$w[$i] = "r.nombre LIKE '%" . addEscapeosParaLike($values['nombre_recoleccion']) . "%'";
			$i++;
		}			
		if ($values['procesador']) {			
			$w[$i] = "c.procesador LIKE '%" . addEscapeosParaLike($values['procesador']) . "%'";
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

		if ($values['fecha_desde']) {
			$w[$i] = "p.fecha_vigencia >= '" . $values['fecha_desde'] . "' AND p.baja_logica = '".FALSE_."'";
			$i++;
		}	
		
		
		if ($values['fecha_hasta']) {
			$w[$i] = "p.fecha_vigencia <= '" . $values['fecha_hasta'] . "' AND p.baja_logica = '".FALSE_."'";
			$i++;
		}	
		
		if($values['para_seleccion']==TRUE_) {
				$w[$i] =" ( " .
							" c.id_recoleccion IS NULL ". 
								" OR ".
							" c.id_recoleccion IN ". 
								" ( ".
									" SELECT r2.id ". 
									" FROM recoleccion r2 ". 
									" WHERE ". 
										" r2.baja_logica = '".TRUE_."' OR ". 
										" ( r2.temporal = '".TRUE_."' AND r2.id <> '".addslashes($values['id_recoleccion_que_esta_siendo_configurada'])."' ) ".
								" ) ".
						" ) ";				
				$i++;							
		} 
		else {
			if($values['id_recoleccion']) {
				$w[$i] = "c.id_recoleccion = '" . addslashes($values['id_recoleccion']) ."'";
				$i++;
			}	
		}

		if($values['id_recoleccion_manual']) {
			if($values['id_recoleccion_manual']!='NULL') {

				$w[$i] = "c.id NOT IN (SELECT crmm.id_central FROM central_recoleccion_manual crmm WHERE crmm.id_recoleccion = '". addslashes($values['id_recoleccion_manual']) ."') ";				
			
			} else {
				$w[$i] = "c.id_recoleccion = '". addslashes($values['id_recoleccion_manual']) . "'"; 
			}
			$i++;
		}
		
		$sql  = "SELECT DISTINCT c.id, ";
		$sql .= "		c.nombre AS nombre_central, ";
		$sql .= "		c.procesador, ";
		$sql .= "		c.descripcion as descripcion_central, ";
		$sql .=	"		tecnologia_recolector.nombre_tecnologia, "; 
		$sql .= "		tecnologia_recolector.accion, ";
		$sql .= "		CONCAT(tecnologia_central.nombre, ' v',tecnologia_central.version) AS nombre_tecnologia_central ";
		$sql .=	"FROM central c ";
		$sql .= "INNER JOIN tecnologia_recolector ON tecnologia_recolector.id = c.id_tecnologia_recolector ";
		$sql .= "INNER JOIN tecnologia_central ON tecnologia_central.id = c.id_tecnologia_central ";
		$sql .= "LEFT JOIN recoleccion r ON r.id = c.id_recoleccion ";
		$sql .= "LEFT JOIN planificacion p ON p.id_recoleccion = r.id ";
		$sql .= "LEFT JOIN central_recoleccion_manual crm ON crm.id_central = c.id ";
		
		if ($w)
		{
			$sql .= "WHERE " . implode(' AND ', $w) . " ";
		}
		
		return $sql;
	}
	
	function getCentralesMonitoreo($idRecoleccion, $soloErroneas){
		$recoleccion = new Recoleccion($idRecoleccion);
				
		$sql = "SELECT ";
		$sql .=		"DISTINCT c.id as id_central, ";
		$sql .= 	"c.nombre as nombre_central, ";
		$sql .= 	"COUNT( DISTINCT re.id ) AS cantidad_ejecuciones ";
		$sql .=	"FROM recoleccion r ";
		
		if($recoleccion->manual==TRUE_){			
			$sql .= "INNER JOIN central_recoleccion_manual crm ON crm.id_recoleccion = r.id ";
			$sql .= "INNER JOIN central c ON crm.id_central = c.id ";
		} else {
			$sql .=	"INNER JOIN central c ON c.id_recoleccion = r.id ";			
		}
		
		$sql .=		"INNER JOIN recoleccion_ejecutada re ON r.id = re.id_recoleccion ";				
		
		if($soloErroneas==TRUE_) {
			$sql .=		"INNER JOIN tarea t ON (t.id_central = c.id AND t.id_recoleccion_ejecutada = re.id) ";									
		}
		
		$sql .= "WHERE r.baja_logica = '".FALSE_."' ";
		$sql .= 	"AND r.temporal = '".FALSE_."' ";
		$sql .= 	"AND c.baja_logica = '".FALSE_."' ";		
		$sql .= 	"AND re.baja_logica = '".FALSE_."' ";
		$sql .= 	"AND r.id ='".$idRecoleccion."' ";
		
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
		
		$sql .= "GROUP BY c.id, c.nombre ";	
		$sql .= "ORDER BY nombre_central ";
				
		$res = $this->getEntity()->_db->leer($sql);
		return $this->_rs2Collection($res);
	}
}
	

?>
