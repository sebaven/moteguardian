<?php
include_once BASE_DIR . 'clases/negocio/clase.Actividad.php';

class ActividadDel extends Action {
	
	function procesar(&$nextAction){
		$actividad = new Actividad($_GET['id']);
		$actividad->baja_logica = TRUE_;
		if ($actividad->save()) {
			$nextAction->setNextAction('ActividadBuscar', 'actividad.eliminado.ok');
		}
		else {
			$nextAction->setNextAction('ActividadBuscar', 'actividad.eliminado.error',array(error => "1"));
		}
	}
	
}
?>