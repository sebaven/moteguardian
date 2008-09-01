<?
include_once BASE_DIR ."clases/negocio/clase.Usuario.php";
include_once BASE_DIR ."clases/listados/clase.Listados.php";
include_once BASE_DIR ."clases/service/clase.Logger.php";
include_once BASE_DIR ."validadores/ShowError.php";
include_once BASE_DIR ."comun/defines_acciones_logger.php";


class UsuarioMod extends Action {
	var $tpl = "tpl/usuario/tpl.UsuarioAlta.php";

	function inicializar() {

		// Titulo del template
		$this->asignar('accion_usuarios', "Modificaci&oacute;n");
		
		// Cargar los datos del Usuario
		$usuario = new Usuario(intval($_GET['id']));

		$this->asignarArray($usuario->toArray());

		// Asigno clave vacia para que se pueda modificar
		$this->asignar('clave',"");
		
		// Roles
		$this->asignar('options_rol', ComboRol());
		
		// Seteo que el nombre de usuario no se pueda modificar
		$this->asignar('modificable', "readonly");
	}


	function validar(&$v) {
		
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
	}


	function procesar(&$nextAction){
		$usuario = new Usuario($_GET['id']);
		
		// Se guarda la clave actual para que no se pise si no posteo ninguna clave
		$clave_actual = $usuario->clave;
		
		//Se copian todos los valores ingresados
		$usuario->setFields($_POST);
		
		// Se valida que la clave ingresada sea null, porque si es null entonces no
		// hay que cambiarla
		if ($_POST['clave'] != "")
			$usuario->clave = md5($_POST["clave"]);
		else
			$usuario->clave = $clave_actual;

		$params["id"] = $usuario->id;
		if ($usuario->update()){
			$nextAction->setNextAction("UsuarioMod", "modificacion.usuario.ok", $params);			
		}
		else{
			$params['error'] = "1";
			$nextAction->setNextAction("UsuarioMod", "modificacion.usuario.error", $params);
		}
	}
}
?>