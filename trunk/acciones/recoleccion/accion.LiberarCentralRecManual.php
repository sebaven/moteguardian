<?php

include_once BASE_DIR . 'clases/negocio/clase.Central.php';
include_once BASE_DIR . 'clases/negocio/clase.CentralRecoleccionManual.php';
include_once BASE_DIR . 'clases/dao/dao.CentralRecoleccionManual.php';

class LiberarCentralRecManual extends Action {
	
	function procesar(&$nextAction){
		$centralRecoleccionManualDAO = new CentralRecoleccionManualDAO();
		
		$centralRecoleccionManual = $centralRecoleccionManualDAO->getByIdCentralIdRecoleccionManual($_GET['id_central'],$_GET['id_recoleccion']);
		$res = $centralRecoleccionManual->delete();
		
		$parametros['id_recoleccion_manual'] = $_GET['id_recoleccion'];
		$parametros['nombre_recoleccion_manual'] = $_GET['nombre_recoleccion_manual'];				
		
		if ($res) {
			$nextAction->setNextAction('RecoleccionManualConf', 'desasignacion.central.ok', $parametros);
		}
		else {
			$parametros['error'] = "1";
			$nextAction->setNextAction('RecoleccionManualConf', 'desasignacion.central.error', $parametros);
		}
	}
	
}


?>