<?php

include_once BASE_DIR . 'clases/negocio/clase.Planificacion.php';
include_once BASE_DIR ."clases/listados/clase.Listados.php";

class PlanificacionDel extends Action {
	
	function procesar(&$nextAction){
		$planificacion = new Planificacion($_GET['id']);
		$planificacion->baja_logica = TRUE_;
		$res = $planificacion->save();
		$parametros['id']= $_GET['id_actividad'];
		if ($res) {
			$nextAction->setNextAction('ActividadAdministracion', 'eliminacion.planificacion.ok', $parametros);
		}
		else {
			$nextAction->setNextAction('ActividadAdministracion', 'eliminacion.planificacion.error', $parametros);
		}
	}
	
}


?>