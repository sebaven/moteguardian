<?
include_once BASE_DIR ."clases/listados/clase.Listados.php";

class UsuarioAdm extends Action
{
	var $tpl = "tpl/usuario/tpl.UsuarioAdm.php";

	function inicializar()
	{
		// Roles
		$this->asignar('options_rol', ComboRol(true));
	}

	function buscar()
	{
		// Cargar Datos de la Busqueda
		$this->asignarArray($_GET);

		$listado = Listados::create('ListadoUsuarios', $_GET);
		$this->asignar("listado", $listado->imprimir_listado());
	}
	
}
?>