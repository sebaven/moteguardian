<?php
include_once BASE_DIR . 'clases/negocio/clase.ActividadEnviador.php';

class ActividadEnvioDel extends Action
{
	
	function procesar(&$nextAction){
		$actividadEnviador = new ActividadEnviador($_GET['id']);
		$actividadEnviador->baja_logica = TRUE_;
		$res = $actividadEnviador->save();
		$parametros['id']= $_GET['id_actividad'];
		if ($res) {
			$nextAction->setNextAction('ActividadAdministracion', 'eliminacion.enviador.ok', $parametros);
		}
		else {
			$parametros['error'] = "1";
			$nextAction->setNextAction('ActividadAdministracion', 'eliminacion.enviador.error', $parametros);
		}
	}
	
}
?>