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
		
		// Cargar los datos del Dispositivo
		$dispositivo = new Dispositivo(intval($_GET['id']));

		$this->asignarArray($dispositivo->toArray());

        // Tipos de Dispositivo
        $this->asignar('options_tipo', ComboTipoDispositivo());
        $this->asignar('options_sala', ComboSala());
	}


	/*function validar(&$v) {
		
		$v->add(new Required('usuario', 'usuario.required'));
		$v->add(new Required('id_rol', 'rol.required'));
		
		// Se valida que el password nuevo que eligio no sea igual al password que
		// tenia anteriormente
		// Se obtiene el usuario
		$usuario = new Usuario($_GET['id']);

		if (!$usuario->validarPasswordRepetido($_POST['clave'])){
			
			$v->add(new ShowError('password.repeat'));
		}
		
		// Se valida que la nueva clave tenga mas de 8 caracteres
		if ($_POST['clave']){
			$v->add(new Condition(strlen($_POST['clave']) >= 8,'cambiarPassword.passConfirm.length'));
			$v->add(new Condition(strlen($_POST['clave']) <= 255,'cambiarPassword.passConfirm.length'));
		}
		
		if ((strstr($_POST['email'], ";")) || (strstr($_POST['otros_mails'], ";"))){
			
			$v->add(new ShowError('caracteres.incorrectos'));
		}
	}     */


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