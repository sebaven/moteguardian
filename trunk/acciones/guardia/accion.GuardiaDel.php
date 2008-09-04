<?
include_once BASE_DIR ."clases/negocio/clase.Guardia.php";

class DispositivoDel extends Action {
	var $tpl = "";
	
	function procesar(&$nextAction){
		$guardia = new Guardia($_GET['id']);
		$guardia->baja_logica = 1;
		
		if ($guardia->update()) {
			$nextAction->setNextAction("GuardiaAdm", "eliminacion.guardia.ok");			
		}
		else {
			$nextAction->setNextAction("GuardiaAdm", "eliminacion.guardia.error",array(error => "1"));	
		}
	}
}
?>