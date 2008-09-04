<?php

include_once BASE_DIR . 'clases/negocio/clase.Planificacion.php';
include_once BASE_DIR ."clases/listados/clase.Listados.php";

class PlanificacionDel extends Action {
	
	function procesar(&$nextAction){
		$planificacion = new Planificacion($_GET['id']);
		$planificacion->baja_logica = TRUE_;
		$res = $planificacion->save();
		
		$parametros['id_ronda'] = $_GET['id_ronda'];
		$accion = 'RondaAlta';
				
		if ($res) {
			$nextAction->setNextAction($accion, 'eliminacion.planificacion.ok', $parametros);
		}
		else {
			$parametros['error'] = "1";
			$nextAction->setNextAction($accion, 'eliminacion.planificacion.error', $parametros);
		}
	}
	
}


?>