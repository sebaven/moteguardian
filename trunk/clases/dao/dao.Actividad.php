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
			$nombre = str_replace('\\','\\\\',$values['nombreActividad']);
			$nombre = str_replace('%','\%',$nombre);
			$nombre = str_replace('_','\_',$nombre);		
			$w[$i] = "a.nombre LIKE '%".$nombre. "%'";
			$i++;
		}
			
		if ($values['nombreCentral']){
			$nombre = str_replace('\\','\\\\',$values['nombreCentral']);
			$nombre = str_replace('%','\%',$nombre);
			$nombre = str_replace('_','\_',$nombre);		
			$w[$i] = "c.nombre LIKE '%".$nombre. "%'";
			$i++;
		}
		
			
		$sql  = "SELECT a.id as id, c.nombre as nombreCentral, ";
		$sql .= "a.nombre as nombreActividad ";
		$sql .=	"FROM actividad a ";
		$sql .= "INNER JOIN central c ON c.id = a.id_central ";
		$sql .= "WHERE a.baja_logica = '".FALSE_."' ";
		$sql .= "AND a.temporal = '".FALSE_."' ";
		$sql .= "AND c.baja_logica = '".FALSE_."' ";

		if (count($w)>0)
			$sql .= " AND ".implode(' AND ', $w ) . " ";
		return $sql;
	}

	/**
	* Devuelve la cantidad de Envios para esa Actividad
	* @return (int) 
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
	
	/**
	* Obtengo los id de las actividades_envio y los id de las Tecnologias que tiene dicha Actividad
	* @return (int) 
	*/
	function getSqlIDAETec($id)
	{			
		$sql  = "SELECT ae.id, ae.id_tecnologia_enviador, ae.nombre, ae.inmediato ";
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