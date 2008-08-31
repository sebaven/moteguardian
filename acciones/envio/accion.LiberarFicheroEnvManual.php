<?php

include_once BASE_DIR . 'clases/negocio/clase.Tarea.php';
include_once BASE_DIR . 'clases/negocio/clase.FicheroEnvioManual.php';
include_once BASE_DIR . 'clases/dao/dao.FicheroEnvioManual.php';

class LiberarFicheroEnvManual extends Action {
	
	function procesar(&$nextAction){
		$ficheroEnvioManualDAO = new FicheroEnvioManualDAO();
		
		$ficheroEnvioManual = $ficheroEnvioManualDAO->getByIdFicheroIdEnvioManual($_GET['id_fichero'],$_GET['id_envio']);
		$res = $ficheroEnvioManual->delete();
		
		$parametros['id_envio_manual'] = $_GET['id_envio'];
		$parametros['nombre_envio_manual'] = $_GET['nombre_envio'];	
		
		if ($res) {
			$nextAction->setNextAction('EnvioManualConf', 'desasignacion.fichero.ok', $parametros);
		}
		else {
			$parametros['error'] = "1";
			$nextAction->setNextAction('EnvioManualConf', 'desasignacion.fichero.error', $parametros);
		}
	}
	
}


?>