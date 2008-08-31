<?
class Logout extends Action
{
	var $tpl = "tpl/general/tpl.Login.php";	
	
	function inicializar() {
		$link = Application::getLinkByActionName('Logout');
		RegistryHelper::finish();

		// Como se destruye el registro no están cargadas las acciones, por eso se redirecciona de esta forma
		Application::Redirect($link);
	}
}
?>