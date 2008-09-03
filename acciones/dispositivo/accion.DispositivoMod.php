<?
include_once BASE_DIR ."clases/negocio/clase.Dispositivo.php";
include_once BASE_DIR ."clases/listados/clase.Listados.php";
include_once BASE_DIR ."clases/service/clase.Logger.php";
include_once BASE_DIR ."validadores/ShowError.php";
include_once BASE_DIR ."comun/defines_acciones_logger.php";


class DispositivoMod extends Action {
	var $tpl = "tpl/dispositivo/tpl.DispositivoAlta.php";

	function inicializar() {
		// Titulo del template
		$this->asignar('accion_dispositivos', "Modificaci&oacute;n");
		
 
		$dispositivo = new Dispositivo(intval($_GET['id']));

		// Cargo los datos del dispositivo en la pantalla
		$this->asignarArray($dispositivo->toArray());

        // Cargo los combos
        $this->asignar('options_tipo', ComboTipoDispositivo());
        $this->asignar('options_sala', ComboSala());
        $this->asignar('options_estado', ComboEstadoDispositivo());
	}


	function procesar(&$nextAction){
		$dispositivo = new Dispositivo($_GET['id']);
		
		//Se copian todos los valores ingresados
		$dispositivo->setFields($_POST);
		
		$params["id"] = $dispositivo->id;
		if ($dispositivo->update()){
			$nextAction->setNextAction("DispositivoAdm", "modificacion.dispositivo.ok", $params);			
		}
		else{
			$params['error'] = "1";
			$nextAction->setNextAction("DispositivoAdm", "modificacion.dispositivo.error", $params);
		}
	}
}
?>