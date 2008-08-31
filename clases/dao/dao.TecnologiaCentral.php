<?php
include_once BASE_DIR ."clases/negocio/clase.TecnologiaCentral.php";

class TecnologiaCentralDAO extends AbstractDAO
{
	function getEntity() {
		return new TecnologiaCentral();
	}
	
	function getDistinctNombres() {
		$entity = $this->getEntity();
		
		// *** Armar la consulta *** //
		$sql  = "SELECT DISTINCT nombre as id ";		
		$sql .= "FROM ".$entity->_tablename." WHERE baja_logica ='".FALSE_."' ";
		$sql .= "ORDER BY nombre";
		
		// *** Realizar la consutla y devolver el resultado *** //
		$res = $entity->_db->leer($sql);
		
		// *** Convertir el record set en un array asociativo y devolverlo *** // 
		return $this->_rs2Collection($res);
	}
	
	/**
	 * Devuelve un array con la lista de todos los objetos de la clase definida en getEntity()
	 * 
	 * @access public
	 * @param $orderby (string) nombre del campo por el cual se van a ordenar los registros
	 * @return array contiene todos las entidades
	 */
	function getAll($orderby = '') {
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
		$sql .= " FROM ".$entity->_tablename." WHERE baja_logica ='".FALSE_."' ";
		
		
		if ($orderby)
			$sql .= "ORDER BY $orderby;";
		
		// *** Realizar la consutla y devolver el resultado *** //
		$res = $entity->_db->leer($sql);
		
		// *** Convertir el record set en un array asociativo y devolverlo *** 
		return $this->_rs2Collection($res);
	}
	
	function getSQLGetAll() {
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
		$sql .= " FROM ".$entity->_tablename." WHERE baja_logica ='".FALSE_."' ";
						 
		return $sql;
	}
	
	/* Devuelve un array con todos los objetos que cumplan con la condici�n que el campo $field contenga el valor
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
		$sql .= " WHERE $field = '".addslashes($value)."' ";
		$sql .= " AND baja_logica = '".FALSE_."'";
		
		// *** Realizar la consutla *** //
		$res = $entity->_db->leer($sql);
		
		return $this->_rs2Collection($res);
	}

	function findTecnologiaByNombreYVersion($nombre, $version, $id){
		$entity = $this->getEntity();
				
		// *** Armar la consulta *** //
		$sql  = " SELECT *";
		$sql .= " FROM $entity->_tablename ";
		$sql .= " WHERE nombre = '".addslashes($nombre)."' ";
		$sql .= " AND id <> '".addslashes($id)."' ";
		$sql .= " AND version = '".addslashes($version)."' ";
		
		$sql .= " AND baja_logica = '".FALSE_."'";
		
		// *** Realizar la consutla *** //
		$res = $entity->_db->leer($sql);
			
		return $this->_rs2Collection($res);		
	}
	
	function sePuedeEliminar($id){
		$entity = $this->getEntity();
		
		$sql = "SELECT tc.nombre FROM ".$entity->_tablename." tc INNER JOIN central c ON tc.id = c.id_tecnologia_central WHERE tc.id = '".addslashes($id)."' AND c.baja_logica = '".FALSE_."' AND tc.baja_logica = '".FALSE_."'";

		$res = $entity->_db->leer($sql);
		$resultado = $this->_rs2Collection($res);
				
		if(count($resultado)==0){
			return true; 
		} else {
			return false;
		}		
	}
	
	function getAllNombreVersion(){
		$entity = $this->getEntity();
		
		$sql = "SELECT CONCAT(tc.nombre,' v',tc.version) as nombre_version, tc.id ";
		$sql .= "FROM ".$entity->_tablename." tc ";
		$sql .= "WHERE tc.baja_logica = '".FALSE_."' ";
		$sql .= "ORDER BY nombre_version";

		$res = $entity->_db->leer($sql);
		return $this->_rs2Collection($res);		
	}
}


?>