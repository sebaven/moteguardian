<?php
include_once BASE_DIR . 'clases/negocio/clase.Envio.php';

class EnvioDel extends Action {
	
	function procesar(&$nextAction){
		$envio = new Envio($_GET['id']);
		$envio->baja_logica = TRUE_;
		if ($envio->save()) {
			$nextAction->setNextAction('EnvioBuscar', 'eliminacion.envio.ok');
		}
		else {
			$nextAction->setNextAction('EnvioBuscar', 'eliminacion.envio.error',array(error => "1"));
		}
	}
	
}
?>