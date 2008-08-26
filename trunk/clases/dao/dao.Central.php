<?php
include_once BASE_DIR."clases/negocio/clase.Central.php";

/**
 * @author mhernandez
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
			  
		// Se hace el escapeo de caracteres en las variables recibidas por parametro
		$nombre = addslashes($nombre);
		
		$array = $this->filterByField("nombre",$nombre);
		return $array[0];
	}
	
	 /**
	 * Esta funci�n devuelve una central correspondiente al codigo pasado por parametro
	 * (El codigo es unico)
	 *
	 * @param unknown_type $codigo
	 * @return unknown
	 */
	function getByCodigo($codigo = "") {
			  
		// Se hace el escapeo de caracteres en las variables recibidas por parametro
		$codigo = addslashes($codigo);
		
		$array = $this->filterByField("codigo",$codigo);
		return $array[0];
	}
	
	/**
	* Devuelve una sentencia SQL para realizar la busqueda de centrales de 
	* acuerdo a los filtros especificados
	* 
	* @param $values (array asociativo) contiene como clave los campos a filtrar con los respectivos valores
	* @return (string) sentencia SQL
	*/
	function getSqlFindByParameters($values = '')
	{
		$w = array();
		$w[0] = ( "c.baja_logica= '".FALSE_."'" );
		
		$i = 1;
		if ($values['nombre'])			
			$nombre = str_replace('\\','\\\\',$values['nombre']);
			$nombre = str_replace('%','\%',$nombre);
			$nombre = str_replace('_','\_',$nombre);
			$nombre = str_replace('\'','\\\'',$nombre);
			$w[$i] = "nombre LIKE '%" . addslashes($nombre) . "%'";
			$i++;
			
		if ($values['codigo'])
			$codigo = str_replace('\\','\\\\',$values['codigo']);
			$codigo = str_replace('%','\%',$codigo);
			$codigo = str_replace('_','\_', $codigo);
			$codigo = str_replace('\'','\\\'',$codigo);
			$w[$i] = "codigo LIKE '%" . addslashes($codigo) . "%'";
			$i++;
			
						
		if ($values['id_tecnologia_recolector'])
			$w[$i] = "id_tecnologia_recolector = '" . addslashes($values['id_tecnologia_recolector']) . "'";
			$i++;
			
		$sql  = "SELECT c.id, "; 
		$sql .= "		c.nombre, ";
		$sql .= "		c.codigo, ";
		$sql .=	"		tecnologia_recolector.nombre_tecnologia, "; 
		$sql .= "		tecnologia_recolector.accion ";
		$sql .=	"FROM central c ";
		$sql .= "INNER JOIN tecnologia_recolector ON tecnologia_recolector.id = c.id_tecnologia_recolector ";

		if ($w)
		{
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
		$sql .= " FROM $entity->_tablename ";
		$sql .= " WHERE $field = '$value'";
		$sql .= " AND baja_logica='".FALSE_."'";
		
		// *** Realizar la consutla *** //
		$res = $entity->_db->leer($sql);
		
		return $this->_rs2Collection($res);
	}
	
}
	
?>