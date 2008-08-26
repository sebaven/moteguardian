<?php
include_once BASE_DIR."clases/negocio/clase.ActividadEnvio.php";
include_once BASE_DIR."clases/dao/dao.Planificacion.php";

class ActividadEnvioDAO extends AbstractDAO
{
	function getEntity()
	{
		return new ActividadEnvio();
	}
	
	function findInmediatosByActividad($idActividad) {
		$entity = $this->getEntity();
		
		// *** Armar la consutla *** //
		$sql = "SELECT * FROM actividad_envio ";
		$sql.= "WHERE baja_logica='".FALSE_."' ";
		$sql.= "AND temporal='".FALSE_."' ";
		$sql.= "AND id_actividad = '".addslashes($idActividad)."' ";
		$sql.= "AND inmediato = '".TRUE_."'";
		
		// *** Realizar la consutla *** //
		$res = $entity->_db->leer($sql);
		
		return $this->_rs2Collection($res);
		
	}

	function filterByFecha($fechaDesde,$fechaHasta) {
		$planificacionDAO = new PlanificacionDAO();
		$planificaciones = $planificacionDAO->filterByFecha($fechaDesde,$fechaHasta);
		$actividadesIds = array();
		$actividades = array();
		foreach($planificaciones as $planificacion) {
			if(!(empty($planificacion->id_actividad_envio))) {
				// Planificacion de recoleccion
				if(!isset($actividadesIds[$planificacion->id_actividad_envio])) {
					// Filtro por si se disparan por diversas planificaciones la misma actividad
					$actividadesIds[$planificacion->id_actividad_envio] = $planificacion->id_actividad_envio;
					$actividad = new ActividadEnvio();
					$actividad->load($planificacion->id_actividad_envio);
					if($actividad->baja_logica==FALSE_) {
						$actividades[] = $actividad;
					}
				}
			}
		}
		
		return $actividades;
	}
	
	function filterByIdActividad($id_actividad) {
		$entity = $this->getEntity();
		
    	$sql = "SELECT * FROM ".$entity->_tablename." WHERE baja_logica = '".FALSE_."' AND temporal = '".FALSE_."' AND id_actividad = '".$id_actividad."' ";
    	
    	$rs = $entity->_db->leer($sql);
    	
    	return $this->_rs2Collection($rs);    	
    }
	
}
?>