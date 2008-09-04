<?
include_once BASE_DIR."clases/listados/clase.Listados.php";

class DispositivoAdm extends Action
{
	var $tpl = "tpl/dispositivo/tpl.DispositivoAdm.php";

	function inicializar()
	{
		// Cargo los combos        
        $this->asignar('options_usuario', ComboUsuario());        
	}

	
	function buscar()
	{
		// Recargo los datos en pantalla para que se sepa que busqué
		$this->asignarArray($_GET);

		$listado = Listados::create('ListadoGuardias', $_GET);
		$this->asignar("listado", $listado->imprimir_listado());
	}	
}
?>