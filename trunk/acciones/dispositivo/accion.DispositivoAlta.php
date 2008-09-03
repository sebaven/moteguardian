<?
include_once BASE_DIR ."clases/negocio/clase.Dispositivo.php";
include_once BASE_DIR ."clases/listados/clase.Listados.php";
//include_once BASE_DIR ."validadores/UsuarioExisteValidator.php";
include_once BASE_DIR ."validadores/CaracteresInvalidos.php";
include_once BASE_DIR ."clases/service/clase.Logger.php";
include_once BASE_DIR ."comun/defines_acciones_logger.php";
//include_once BASE_DIR ."validadores/Emailvalidator.php";
include_once BASE_DIR ."validadores/ShowError.php";

class DispositivoAlta extends Action {
    var $tpl = "tpl/dispositivo/tpl.DispositivoAlta.php";
    
    function inicializar() {

        // Titulo del template
        $this->asignar('accion_dispositivos', "Alta");

		// Cargo los combos
        $this->asignar('options_tipo', ComboTipoDispositivo());
        $this->asignar('options_estado', ComboEstadoDispositivo());
        $this->asignar('options_sala', ComboSala());
    }

    function guardarDispositivo()
    {        
        $dispositivo = new Dispositivo();        
        $dispositivo->setFields($_POST);
        return $dispositivo->save();
    }

    function procesar(&$nextAction)
    {
        if ($this->guardarDispositivo()){
            $nextAction->setNextAction('DispositivoAdm', 'alta.dispositivo.ok');
        }
        else{
            $nextAction->setNextAction('DispositivoAdm', 'alta.dispositivo.error',array(error => "1"));
        }
    }    
}
?>