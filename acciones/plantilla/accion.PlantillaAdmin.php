<?
include_once BASE_DIR ."clases/listados/clase.Listados.php";

class PlantillaAdmin extends Action
{
	var $tpl = "tpl/plantilla/tpl.PlantillaAdmin.php";

	function inicializar()
	{
				
	}

	function buscar()
	{		
		$listado = Listados::create('ListadoPlantillas', $_GET);
		$this->asignar("listado", $listado->imprimir_listado());
	}
	
}
?>