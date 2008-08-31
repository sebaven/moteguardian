<?
/**
 * Acci�n en la que se cambia el password del usuario que est� logueado actualmente en el sistema.
 */
class CambiarPassword extends Action {
	
	var $tpl = "tpl/general/tpl.CambiarPassword.php";

	function validar(&$v){
		$v->add(new Required('passActual', 'cambiarPassword.passActual.required'));
		$v->add(new Required('passNuevo', 'cambiarPassword.passNuevo.required'));
		$v->add(new Required('passConfirm', 'cambiarPassword.passConfirm.required'));
		
		// Se valida que la clave actual sea la actual
		// Se obtiene el usuario
		$usuario = new Usuario(RegistryHelper::getIdUsuario());
		$v->add(new Condition(!$usuario->validarPasswordRepetido($_POST['passActual']),'cambiarPassword.passActual.incorrecto'));
		
		// Se valida que la nueva clave sea distinta a la clave actual
		$v->add(new Condition($usuario->validarPasswordRepetido($_POST['passNuevo']),'password.repeat'));

		// Se valida que la nueva clave y la confirmacion sean identicas
		$v->add(new Condition($_REQUEST['passNuevo'] == $_REQUEST['passConfirm'],'cambiarPassword.passConfirm.diferente'));
		
		// Se valida que la nueva clave tenga mas de 8 caracteres y menos de 255 (inclusive)
		$v->add(new Condition(strlen($_REQUEST['passNuevo']) >= 8,'cambiarPassword.passConfirm.length'));
		$v->add(new Condition(strlen($_REQUEST['passNuevo']) <= 255,'cambiarPassword.passConfirm.length'));
	}

	function procesar(&$nextAction){
		$usuario = new Usuario(RegistryHelper::getIdUsuario());

		$usuario->clave = md5($_REQUEST['passNuevo']);
		
		
		if ($usuario->update()) {
			$nextAction->setNextAction('Inicio','cambiarPassword.ok');
		}
		else {
			$nextAction->setNextAction('Inicio','cambiarPassword.error',array(error => "1"));
		}
	}
}
?>