<?
include_once BASE_DIR ."clases/negocio/clase.Usuario.php";
include_once BASE_DIR ."clases/listados/clase.Listados.php";
include_once BASE_DIR ."validadores/UsuarioExisteValidator.php";
include_once BASE_DIR ."validadores/CaracteresInvalidos.php";
include_once BASE_DIR ."clases/service/clase.Logger.php";
include_once BASE_DIR ."comun/defines_acciones_logger.php";
include_once BASE_DIR ."validadores/Emailvalidator.php";
include_once BASE_DIR ."validadores/ShowError.php";

class UsuarioAlta extends Action {
	var $tpl = "tpl/usuario/tpl.UsuarioAlta.php";
	
	function inicializar() {

		// Titulo del template
		$this->asignar('accion_usuarios', "Alta");
		
		// Roles
		$this->asignar('options_rol', ComboRol());
		
		// Se limpia la clave
		$this->asignar('clave', "");
	}

	function validar(&$v)
	{
		$v->add(new Required('usuario', 'usuario.required'), false);
		$v->add(new Required('clave', 'password.required'), false);		
		$v->add(new Required('id_rol', 'rol.required'), false);
		
		// Se valida que el usuario que se esta creando no contenga caracteres invalidos
		// Solo podra tener caracteres a-z, A-Z y 0-9
		$v->add(new CaracteresInvalidos('usuario', 'usuario.caracteres.invalidos'), false);

		$v->add(new UsuarioExisteValidator($_POST['usuario'], 'alta.usuario.existente'), false);
		
		// Se valida que la nueva clave tenga mas de 5 caracteres
		$v->add(new Condition(strlen($_POST['clave']) >= 8,'cambiarPassword.passConfirm.length'));
		$v->add(new Condition(strlen($_POST['clave']) <= 255,'cambiarPassword.passConfirm.length'));
		
		//Si el usuario completo el campo "mail" este se verifica
		if ($_POST['email'])
		{
			// Se verifica que el mail sea v�lido.
			// La funcion "trim" saca los espacios vacios de una cadena.
			$v->add(new EmailValidator( trim( $_POST['email'] ), 'usuario.email.invalido'), false);
		}
		
		//Si el usuario completo el campo "otro mail" este se verifica 
		if ($_POST['otros_mails'])
		{
			// se fija si hay mas de un mail
			if (strpos($_POST['otros_mails'] , ';')==false)
			{  
				// Si hay un solo mail se verifica que sea v�lido
				$v->add(new EmailValidator( trim($_POST['otros_mails']), 'usuario.otros_mails.invalido'), false);
		
			}else{
				//se obtienen todos los mails
				$mails = explode(";", $_POST['otros_mails']);
				
				for ($i = 0; $i < count($mails) ; $i++) {
     				// Se verifica que cada mail sea v�lido
     				$v->add(new EmailValidator( trim($mails[$i]), 'usuario.otros_mails.invalido'), false);
				}
			}
		}
		
		if ((strstr($_POST['email'], ";"))){
			
			$v->add(new ShowError('caracteres.incorrectos'));
		}
	}
	
	
	function defaults()
	{
		parent::defaults();
		
		// Se oculta el combo de clubes
		$this->asignar('mostrarComboClubes', "none");
	}

	function guardarUsuario(){
		
		$usuario = new Usuario();
		// Antes de dar de alta el nuevo usuario se eliminan los usuarios que puedan
		// llegar a tener ese nombre con baja logica = 1
		$usuario->deleteUsuario($_POST['usuario']);
		
		$usuario->usuario = $_POST['usuario'];
		$usuario->clave = md5($_POST['clave']);
		$usuario->nombre = $_POST['nombre'];
		$usuario->apellido = $_POST['apellido'];
		//se utiliza la funcion trim encargada de sacarle espacios a una cadena
		$usuario->email = trim( $_POST['email'] );
		$usuario->otros_mails = trim( $_POST['otros_mails'] );
		$usuario->telefono1 = $_POST['telefono1'];
		$usuario->telefono2 = $_POST['telefono2'];
		$usuario->id_club = $_POST['id_club'];
		$usuario->id_rol = $_POST['id_rol'];
		$usuario->baja_logica = 0;
		
		// Indica si el trabajo se ha guardado correctamente o no.
		return $usuario->save();
	}

	function procesar(&$nextAction)
	{
		if ($this->guardarUsuario()){
			$nextAction->setNextAction('UsuarioAlta', 'alta.usuario.ok');
			Logger::register(ALTA_USUARIO_WEB,'Alta de usuario ' . $_POST['usuario']);
		}
		else{
			$nextAction->setNextAction('UsuarioAlta', 'alta.usuario.error');
		}
	}	
}
?>