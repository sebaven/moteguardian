<?php

include_once BASE_DIR . 'clases/negocio/clase.Central.php';

class LiberarCentral extends Action {
	
	function procesar(&$nextAction){
		$central = new Central($_GET['id']);
		$central->id_recoleccion = 'NULL';
		$res = $central->save();
		
		$parametros['id_recoleccion'] = $_GET['id_recoleccion'];
				
		if ($res) {
			$nextAction->setNextAction('RecoleccionConf', 'liberacion.central.ok', $parametros);
		}
		else {
			$parametros['error'] = "1";
			$nextAction->setNextAction('RecoleccionConf', 'liberacion.central.error', $parametros);
		}
	}
	
}


?>