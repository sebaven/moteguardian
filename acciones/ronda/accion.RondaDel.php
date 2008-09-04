<?
include_once BASE_DIR ."clases/negocio/clase.Ronda.php";

class RondaDel extends Action {
	var $tpl = "";
	
	function procesar(&$nextAction){
		$ronda = new Ronda($_GET['id']);
		$ronda->baja_logica = 1;
		
		if ($ronda->update()) {
			$nextAction->setNextAction("RondaAdm", "eliminacion.ronda.ok");			
		}
		else {
			$nextAction->setNextAction("RondaAdm", "eliminacion.ronda.error",array(error => "1"));	
		}
	}
}
?>