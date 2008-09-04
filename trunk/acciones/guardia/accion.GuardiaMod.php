<?
include_once BASE_DIR ."clases/negocio/clase.Guardia.php";
include_once BASE_DIR ."clases/listados/clase.Listados.php";


class GuardiaMod extends Action {
	var $tpl = "tpl/guardia/tpl.GuardiaAlta.php";

	function inicializar() {
		// Titulo del template
		$this->asignar('accion_guardias', "Modificaci&oacute;n");
		 
		$guardia = new Guardia(intval($_GET['id']));
		// Cargo los datos del dispositivo en la pantalla
		$this->asignarArray($guardia->toArray());
	}


	function procesar(&$nextAction){
		$guardia = new Guardia($_GET['id']);
		
		//Se copian todos los valores ingresados
		$guardia->setFields($_POST);
		
		$params["id"] = $guardia->id;
		if ($guardia->update()){
			$nextAction->setNextAction("GuardiaAdm", "modificacion.guardia.ok", $params);			
		}
		else{
			$params['error'] = "1";
			$nextAction->setNextAction("GuardiaAdm", "modificacion.guardia.error", $params);
		}
	}
}
?>