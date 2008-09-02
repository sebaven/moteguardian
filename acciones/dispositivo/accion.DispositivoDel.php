<?
include_once BASE_DIR ."clases/negocio/clase.Dispositivo.php";
include_once BASE_DIR ."clases/service/clase.Logger.php";
include_once BASE_DIR ."comun/defines_acciones_logger.php";

class DispositivoDel extends Action {
	var $tpl = "";
	
	function procesar(&$nextAction){
		$dispositivo = new Dispositivo($_GET['id']);
		$dispositivo->baja_logica = 1;
		
		if ($dispositivo->update()) {
			$nextAction->setNextAction("DispositivoAdm", "eliminacion.dispositivo.ok");			
		}
		else {
			$nextAction->setNextAction("DispositivoAdm", "eliminacion.dispositivo.error",array(error => "1"));	
		}
	}
}
?>