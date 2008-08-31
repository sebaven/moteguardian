<?
include_once(BASE_DIR . "validadores/UsuarioValidator.php");

class Login extends Action
{
	var $tpl = "tpl/general/tpl.Login.php";	
	
	function inicializar(){
		$this->asignar("clave","");
	}
	
	function validar(&$v)
	{
		$v->add(new Required('nombre', 'nombre.required'));
		$v->add(new Required('clave', 'password.required'));
		$v->add(new UsuarioValidator($_REQUEST['nombre'], $_REQUEST['clave'], 'usuario_clave_incorrectos'));
	}

	function procesar(&$nextAction)
	{
		$usuario = new Usuario();
		$datos = $usuario->getByNombreUsuario($_REQUEST['nombre']);
		$rol = $usuario->getRolByNombreUsuario($_REQUEST['nombre']);

		RegistryHelper::registerUser($datos['usuario'][0], $datos['id'][0], $rol);
		
		//Codigo de ReturnTo
		$returnAction = $_GET['returnAction'];
				
	 	if (isset($_GET['returnAction']))
 			$nextAction->setRedirect("?".urldecode($returnAction));
 		else
			$nextAction->setNextAction("Inicio");
	}
}
?>
