<?php
include_once BASE_DIR."clases/negocio/clase.Host.php";

/**
 * @author aamantea
 *
 */

class HostDAO extends AbstractDAO
{
	function getEntity()
	{
		return new Host();
	}

	
	/**
	 * Esta funci�n devuelve un host correspondiente al nombre pasado por parametro
	 * (El nombre es unico)
	 *
	 * @param unknown_type $nombre
	 * @return unknown
	 */
	function getByNombre($nombre = "") {
			  
		// Se hace el escapeo de caracteres en las variables recibidas por parametro
		$nombre = addslashes($nombre);
		
		$array = $this->filterByField("nombre",$nombre);
		return $array[0];
	}
	
	/**
	* Devuelve una sentencia SQL para realizar la busqueda de hostes de 
	* acuerdo a los filtros especificados
	* 
	* @param $values (array asociativo) contiene como clave los campos a filtrar con los respectivos valores
	* @return (string) sentencia SQL
	*/
	function getSqlFindByParameters($values = '')
	{
		$w = array();
		$w[0] = ( "h.baja_logica= '".FALSE_."'" );
		
		$i = 1;
		if ($values['nombre']) {			
			$nombre = str_replace('\\','\\\\',$values['nombre']);
			$nombre = str_replace('%','\%',$nombre);
			$nombre = str_replace('_','\_',$nombre);
			$nombre = str_replace('\'','\\\'',$nombre);
			$w[$i] = "h.nombre LIKE '%" . addslashes($nombre) . "%'";
			$i++;
		}
					
		if ($values['id_tecnologia_enviador']) {
			$w[$i] = "id_tecnologia_enviador = '" . addslashes($values['id_tecnologia_enviador']) . "'";
			$i++;
		}
		
		if($values['id_envio']){
			if($values['id_envio']=='NULL') {
				$w[$i] = "h.id NOT IN (SELECT id_host FROM host_envio) OR h.id IN (SELECT id_host FROM host_envio INNER JOIN envio e WHERE e.baja_logica = '".TRUE_."' OR e.temporal = '".TRUE_."' ) ";		
			}
		}
			
		$sql  = "SELECT h.id, "; 
		$sql .= 	"h.nombre AS nombre_host, ";
		$sql .= 	"h.descripcion, ";
		$sql .=		"tecnologia_enviador.nombre_tecnologia, "; 
		$sql .= 	"tecnologia_enviador.accion ";
		$sql .=	"FROM host h ";
		$sql .= 	"INNER JOIN tecnologia_enviador ON tecnologia_enviador.id = h.id_tecnologia_enviador ";
		
		if ($w) {
			$sql .= "WHERE " . implode(' AND ', $w) . " ";
		}

		return $sql;
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

	/**
	 * Devuelve todos los hosts asociados a un envio (que no es temporal ni manual)
	 *
	 * @param unknown_type $id_envio
	 * @return unknown
	 */
	function findByIdEnvio($id_envio) {
		$entity = $this->getEntity();
		
		$sql = "SELECT h.*";
		$sql .= "FROM ".$entity->_tablename." h ";
		$sql .= 	"INNER JOIN host_envio he ON he.id_host = h.id ";
		$sql .= 	"INNER JOIN envio e ON he.id_envio = e.id ";
		$sql .= "WHERE e.id = '".$id_envio."' ";
		$sql .= 	"AND h.baja_logica = '".FALSE_."' ";
		$sql .= 	"AND e.manual = '".FALSE_."' ";
		$sql .= 	"AND e.temporal = '".FALSE_."'";
		
		$res = $entity->_db->leer($sql);
		return $this->_rs2Collection($res);
	}
	
	/**
	 * Devuelve todos los hosts asociados a un envio manual
	 *
	 * @param unknown_type $id_envio
	 * @return unknown
	 */
	function findByIdEnvioManual($id_envio) {
		$entity = $this->getEntity();
		
		$sql = "SELECT h.*";
		$sql .= "FROM ".$entity->_tablename." h ";
		$sql .= 	"INNER JOIN host_envio he ON he.id_host = h.id ";
		$sql .= 	"INNER JOIN envio e ON he.id_envio = e.id ";
		$sql .= "WHERE e.id = '".$id_envio."' ";
		$sql .= 	"AND h.baja_logica = '".FALSE_."' ";
		$sql .= 	"AND e.manual = '".TRUE_."' ";
		$sql .= 	"AND e.temporal = '".FALSE_."'";
		
		$res = $entity->_db->leer($sql);
		return $this->_rs2Collection($res);
	}
	
	function getHostsAsignadosAEnvio($id_envio) {
		$entity = $this->getEntity();
		
		$sql = "SELECT ";
		$sql .= 	"h.id, ";
		$sql .= 	"h.nombre ";	
		$sql .= "FROM ".$entity->_tablename." h ";
		$sql .= 	"INNER JOIN host_envio he ON he.id_host = h.id ";
		$sql .= 	"INNER JOIN envio e ON he.id_envio = e.id ";
		$sql .= "WHERE e.id = '".$id_envio."' ";
		$sql .= 	"AND h.baja_logica = '".FALSE_."'";
		
		$res = $entity->_db->leer($sql);
		return $this->_rs2Collection($res);
	}

	function getHostsNoAsignadosAEnvio($id_envio) {
		$entity = $this->getEntity();
		
		$sql = "SELECT ";
		$sql .= 	"DISTINCT (h.id), ";
		$sql .= 	"h.nombre ";	
		$sql .= "FROM ".$entity->_tablename." h ";
		$sql .= 	"LEFT JOIN host_envio he ON he.id_host = h.id ";
		$sql .= 	"LEFT JOIN envio e ON he.id_envio = e.id ";
		$sql .= "WHERE h.baja_logica = '".FALSE_."' ";		
		$sql .= 	"AND NOT EXISTS( ".
								"SELECT ho.id ".
								"FROM host ho INNER JOIN host_envio hen ON hen.id_host = ho.id INNER JOIN envio en ON en.id = hen.id_envio ".
								"WHERE en.id = '$id_envio' AND ho.id = h.id )";		
		
		$res = $entity->_db->leer($sql);		
		return $this->_rs2Collection($res);
	}
	
	function getHostsNoAsignadosAEnvioManual($id_envio) {
		$entity = $this->getEntity();
		
		$sql = "SELECT ";
		$sql .= 	"DISTINCT (h.id), ";
		$sql .= 	"h.nombre ";	
		$sql .= "FROM ".$entity->_tablename." h ";
		$sql .= 	"LEFT JOIN host_envio he ON he.id_host = h.id ";
		$sql .= 	"LEFT JOIN envio e ON he.id_envio = e.id ";
		$sql .= "WHERE h.baja_logica = '".FALSE_."' ";		
		$sql .= 	"AND h.id NOT IN (SELECT ho.id FROM host ho INNER JOIN host_envio hen ON hen.id_host = ho.id INNER JOIN envio en ON en.id = hen.id_envio WHERE en.id = $id_envio AND en.baja_logica = '".FALSE_."' AND ho.baja_logica ='".FALSE_."') ";		
		
		$res = $entity->_db->leer($sql);
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
		$sql .= " WHERE ".$field." = '".$value."'";
		$sql .= " AND baja_logica='".FALSE_."'";
		
		// *** Realizar la consutla *** //
		$res = $entity->_db->leer($sql);
		
		return $this->_rs2Collection($res);
	}
	
}
	
?>