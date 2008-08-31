<?php
include_once BASE_DIR."clases/negocio/clase.Plantilla.php";

/**
 * @autor cgalli
 */
class PlantillaDAO extends AbstractDAO {
	function getEntity() {
		return new Plantilla();
	}

	/**
	 * Obtiene el sql para obtener todos los nombres de los tipos de filtros
	 * de los nodos de una plantilla
	 * 
	 * @autor cgalli
	 */
	function getSqlFiltros($idPlantilla)
	{			
		$sql  = "SELECT tf.nombre as tipo_filtro, "; 
		$sql .= 	"concat_ws('-',np.id,tf.accion) as id, "; 
		$sql .= 	"np.nombre as nombre, ";		
		$sql .=		"tf.id as id_tipo_filtro, ";
		$sql .= 	"tf.accion as accion_conf ";	
		$sql .=	"FROM nodo_plantilla np ";
		$sql .= "INNER JOIN tipo_filtro tf ON np.id_tipo_filtro = tf.id ";
		$sql .= "WHERE np.id_plantilla ='".addslashes($idPlantilla)."' ";
		$sql .= "AND np.baja_logica ='".FALSE_."'";		
		return $sql;
	}	

	function getUltimoIdNodoPlantilla($idPlantilla)
	{		
		$sql =  "SELECT id ";
		$sql .= "FROM nodo_plantilla ";
		$sql .= "WHERE id_plantilla ='".addslashes($idPlantilla)."' ";
		$sql .= "ORDER BY id DESC ";
		$sql .= "LIMIT 1 ";
		
		$array= $this->getEntity()->_rs2Array($this->getEntity()->_db->leer($sql));

		return $array['id'][0];
	}
	
	function getSqlPlantillas($values = '')
	{			
		$w = array();
		
		if ($values['nombre']) {			
			$w[] = "nombre LIKE '%" . addEscapeosParaLike($values['nombre']) . "%'";						
		}
			
		$sql  = "SELECT nombre, id "; 
		$sql .=	"FROM plantilla ";
		$sql .= "WHERE baja_logica = '".FALSE_."' AND temporal = '".FALSE_."' ";				
				
		if ($w)
		{
			$sql .= " AND ".implode(' AND ', $w) . " ";
		}
		
		return $sql;		
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
		// *** Armar la consulta *** //
		$sql  = " SELECT *";
		$sql .= " FROM plantilla ";
		$sql .= " WHERE $field = '".addslashes($value)."'";
		$sql .= " AND baja_logica ='".FALSE_."'";
		$sql .= " AND temporal ='".FALSE_."'";
		
		// *** Realizar la consutla *** //
		$res = $this->getEntity()->_db->leer($sql);
		
		return $this->_rs2Collection($res);
	}

	function getCircuitosIncompletosPlantillaSinActividad($params){
		$entity = $this->getEntity();
		
		$sql =	"SELECT ";
		$sql .= 	"nombre, ";
		$sql .= 	"id ";
		$sql .=	"FROM plantilla ";
		$sql .=	"WHERE id NOT IN (SELECT id_plantilla FROM actividad a WHERE a.baja_logica = '".FALSE_."' AND id_plantilla IS NOT NULL) ";
		$sql .=		"AND baja_logica = '".FALSE_."' ";
		$sql .=		"AND temporal = '".FALSE_."' ";
		
		if($params['plantilla']) $sql .= "AND nombre LIKE '%".addEscapeosParaLike($params['plantilla'])."%' ";
		
		if( $params['central'] || 
			$params['procesador'] || 
			$params['id_tecnologia_central'] || 
			$params['id_tecnologia_recolector'] || 
			$params['id_tecnologia_enviador'] || 
			$params['recoleccion'] || 
			$params['actividad'] || 
			$params['envio'] || 
			$params['host'] ){ return; }
		
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
		$sql .= " FROM ".$entity->_tablename." WHERE baja_logica ='".FALSE_."' AND temporal ='".FALSE_."'";
		
		if ($orderby)
			$sql .= "ORDER BY $orderby";
		
		// *** Realizar la consutla y devolver el resultado *** //
		$res = $entity->_db->leer($sql);
		
		// *** Convertir el record set en un array asociativo y devolverlo *** 
		return $this->_rs2Collection($res);
	}
}
?>
