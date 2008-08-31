<?php
include_once BASE_DIR."clases/negocio/clase.Actividad.php";
include_once BASE_DIR."clases/dao/dao.Planificacion.php";

class ActividadDAO extends AbstractDAO
{
	function getEntity()
	{
		return new Actividad();
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
		
		if ($values['nombreActividad']){					
			$w[$i] = "a.nombre LIKE '%".addEscapeosParaLike($values['nombreActividad']). "%'";
			$i++;
		}
					
		$sql  = "SELECT ";
		$sql .= 	"a.id as id, ";
		$sql .= 	"p.nombre as nombre_plantilla, ";
		$sql .= 	"a.nombre as nombre_actividad ";
		$sql .=	"FROM actividad a ";
		$sql .= 	"INNER JOIN plantilla p ON p.id = a.id_plantilla ";
		$sql .= "WHERE a.baja_logica = '".FALSE_."' ";		
		$sql .= 	"AND p.baja_logica = '".FALSE_."' ";
		$sql .= 	"AND p.temporal = '".FALSE_."' ";
		$sql .= 	"AND a.temporal = '".FALSE_."' ";
		

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
		$sql .= "AND ae.baja_logica = '".FALSE_."' ";
		$sql .= "AND ae.temporal = '".FALSE_."' ";
		$sql .= "AND a.temporal = '".FALSE_."' ";

		// *** Realizar la consutla *** //
		$res = $this->getEntity()->_db->leer($sql);
		$a = $this->_rs2Array($res);
		return $a["cantidad"][0];
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
		$sql .= " WHERE $field = '".addslashes($value)."' ";
		$sql .= " AND baja_logica ='".FALSE_."' ";		
		$sql .= " AND temporal ='".FALSE_."' ";		
		
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
		$sql .= " WHERE baja_logica='".FALSE_."' ";
		$sql .= " AND temporal='".FALSE_."'";
		
		
		if ($orderby)
			$sql .= "ORDER BY $orderby;";
		
		// *** Realizar la consutla y devolver el resultado *** //
		$res = $entity->_db->leer($sql);
		
		// *** Convertir el record set en un array asociativo y devolverlo *** 
		return $this->_rs2Collection($res);
	}
	
	function getNombreActividadMenosLaPasadaPorParametro($id_actividad, $nombre_actividad) {
		$entity = $this->getEntity();
		
		$sql = "SELECT nombre ";
		$sql .= "FROM ".$entity->_tablename." r ";
		$sql .= "WHERE r.id <> '".addslashes($id_actividad)."' ";
		$sql .= 	"AND r.baja_logica = '".FALSE_."' ";		
		$sql .= 	"AND r.temporal = '".FALSE_."' ";		
		$sql .= 	"AND r.nombre = '".addslashes(trim($nombre_actividad))."' ";
		
		$res = $entity->_db->leer($sql);
		return $this->_rs2Collection($res);		
	}
	
	/*
	 * Devuelve un array con las actividades que se deben lanzar para recoleccion entre esas 2 fechas, la 1ra exclusive y la 2da inclusive
	 */
	function filterByFecha($fechaDesde,$fechaHasta) {
		$planificacionDAO = new PlanificacionDAO();
		$planificaciones = $planificacionDAO->filterByFecha($fechaDesde,$fechaHasta);
		$actividadesIds = array();
		$actividades = array();
		foreach($planificaciones as $planificacion) {
			if(!(empty($planificacion->id_actividad))) {
				// Planificacion de recoleccion
				if(!isset($actividadesIds[$planificacion->id_actividad])) {
					// Filtro por si se disparan por diversas planificaciones la misma actividad
					$actividadesIds[$planificacion->id_actividad] = $planificacion->id_actividad;
					$actividad = new Actividad();
					$actividad->load($planificacion->id_actividad);
					if(($actividad->temporal == FALSE_)&&($actividad->baja_logica == FALSE_)) {
						$actividades[] = $actividad;
					}
				}
			}
		}
		
		return $actividades;
	}
	
}
?>