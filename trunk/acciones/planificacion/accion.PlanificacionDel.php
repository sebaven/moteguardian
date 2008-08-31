<?php

include_once BASE_DIR . 'clases/negocio/clase.Planificacion.php';
include_once BASE_DIR ."clases/listados/clase.Listados.php";

class PlanificacionDel extends Action {
	
	function procesar(&$nextAction){
		$planificacion = new Planificacion($_GET['id']);
		$planificacion->baja_logica = TRUE_;
		$res = $planificacion->save();
		
		if($_GET['id_recoleccion']) {
			$parametros['id_recoleccion'] = $_GET['id_recoleccion'];
			$accion = 'RecoleccionConf';	
		} else {
			$parametros['id_envio'] = $_GET['id_envio'];
			$accion = 'EnvioAlta';
		}
		
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