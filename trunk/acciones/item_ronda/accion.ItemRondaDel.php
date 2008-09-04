<?php
include_once BASE_DIR . 'clases/negocio/clase.ItemRonda.php';
include_once BASE_DIR ."clases/listados/clase.Listados.php";

class ItemRondaDel extends Action {
	
	function procesar(&$nextAction){
		$itemRonda = new ItemRonda($_GET['id']);
		$itemRonda->baja_logica = TRUE_;
		$res = $itemRonda->save();
		
		$parametros['id_ronda'] = $_GET['id_ronda'];
		$accion = 'RondaAlta';
				
		if ($res) {
			$nextAction->setNextAction($accion, 'eliminacion.item.ronda.ok', $parametros);
		}
		else {
			$parametros['error'] = "1";
			$nextAction->setNextAction($accion, 'eliminacion.item.ronda.error', $parametros);
		}
	}
	
}


?>