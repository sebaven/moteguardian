<?
/**
 * Clase Base para acceder a la base de datos. Provee los m�todos b�sicos de acceso.
 * 
 * ----------------------------------------
 *      iOnix - PHP Framework - 2006
 * ----------------------------------------
 * Jorge Barbosa      <jmbarbosa@gmail.com>
 * Alejandro Molinari <amolinari@gmail.com>
 * ----------------------------------------
 * 
 * @package data
 * @version 1.0
 * @abstract
 * @author jbarbosa
 */ 
class AbstractDAO
{
	/**
	 * Constructor de la clase
	 *
	 * @access public
	 * @return AbstractDAO
	 */
	function AbstractDAO()
	{
	}

	/**
	 * Este m�todo debe sobreescribirse para indicar cual es la entidad de la cual se van a obtener
	 * los datos
	 * 
	 * @access public
	 * @return (object) referencia a un objeto de la clase AbstractEntity de la cual se van a recuperar
	 * los datos
	 */
	function getEntity()
	{
		trigger_error("Falta especificar la entidad sobre la cual va a buscar el DAO");
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
		$sql .= " WHERE baja_logica = '".FALSE_."' ";
		
		if ($orderby)
			$sql .= "ORDER BY $orderby;";
		
		// *** Realizar la consutla y devolver el resultado *** //
		$res = $entity->_db->leer($sql);
		
		// *** Convertir el record set en un array asociativo y devolverlo *** 
		return $this->_rs2Collection($res);
	}

	/**
	 * Devuelve el objeto de la clase definida en getEntity() cuyo id es el especificado
	 * 
	 * @access public
	 * @param $id (int) es el identificador del registro que se va a cargar.
	 * @return (object) objeto de la clase definida en getEntity() cuyo id es el especificado. Vac�o si no existe
	 */ 
	function getById($id = "")
	{	  
		$entity = $this->getEntity();
		
		// Verificar parametro
		if (empty($id))
		{
			return '';
		}
		
		$sql  = "SELECT * FROM $entity->_tablename ";
		$sql .= "WHERE id = $id";
		// *** Realizar la consutla *** //
		$res = $entity->_db->leer($sql);
		
		return $this->_rs2Entity($res);
	}
	
	/**
	 * Devuelve un array con todos los objetos que cumplan con la condici�n que el campo $field contenga el valor
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
		
		// *** Realizar la consutla *** //
		$res = $entity->_db->leer($sql);
		
		return $this->_rs2Collection($res);
	}
	
	/**
	 * Devuelve un array con todos los registros que cumplen la condici�n especificada en '$condition'.
	 * 
	 * @access private
	 * @param $condition (string) es la condici�n de filtrado
	 * @return array con los objetos que cumplen la condici�n
	 */ 
	function filterBy($condition)
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
		$sql .= "WHERE " . $condition;

		// *** Realizar la consutla *** //
		$res = $entity->_db->leer($sql);
		
		return $this->_rs2Collection($res);
	}
	
	/**
	 * Convierte el resource set especificado en un objeto de clase especificada en getEntity()
	 * 
	 * @access private
	 * @param $rs (object) resource set
	 * @return (object) referencia a un objeto de la clase definida en getEntity()
	 */
	function _rs2Entity($rs)
	{
		$entity = $this->getEntity();		
		$db = $entity->_db;

		if($db->numero_filas($rs) == 0) {
			return NULL;
		}
		
		// Tomar el �nico elemento del resource set
		$r = $db->reg_array($rs);
		
		// Iterar por cada uno de los campos los atributos de la entidad
		foreach ($r as $key => $value)
		{
			$entity->$key = $value;
		}

		return $entity;
	}
	
	/**
	 * Convierte el resource set especificado en un array de objetos de la clase definida en getEntity()
	 * 
	 * @access private
	 * @param $rs (object) resource set
	 * @return (array) de objetos de la clase definida en getEntity()
	 */
	function _rs2Collection($rs)
	{
		$entity = $this->getEntity();		
		$db = $entity->_db;
		$collection = array();

		if($db->numero_filas($rs) == 0) {
			return $collection;
		}

		
		// Iterar por cada uno de los registros
		while($r = $db->reg_array($rs))
		{
			// Iterar por cada uno de los campos los atributos de la entidad
			foreach ($r as $key => $value)
			{
				$entity->$key = $value;
			}
			$collection[] = clone $entity;
		}
		return $collection;
	}
	
	/**
	 * Devuelve un array asociativo con los registros contenidos en el recordset '$rs'.
	 * 
	 * @access private	
	 * @param $rs es el resultado de alguna consulta realizada por medio de la clase 'db'.
	 * @return array asociativo con el siguiente formato: valor['campo1'][0]; valor['campo1'][1]; valor['campo2'][0]; etc.	  
	 */ 
	function _rs2array($rs)
	{
		$entity = $this->getEntity();
		$db = $entity->_db;
		$res = array();

		// Pasar los campos a un registro asociativo
		while($r = $db->reg_array($rs))
		{
			// NOTA: 
			// $r es de la forma:
			// 		$r[0] = 55
			// 		$r['id'] = 55
			// 		O sea que esta indexado por un entero y por un string
			
			// Armar un array asociativo obviando los indices numericos					
			foreach ($r as $key => $valor)
			{
					if (strval(intval($key)) == strval($key))
					{
						// Si es numerico paso al siguiente campo
						continue;
					}

					// Agregar valor
					$res[$key][] = $valor;					
			}
		}

		// Devolver el array asociativo
		return $res;
	}
}
?>