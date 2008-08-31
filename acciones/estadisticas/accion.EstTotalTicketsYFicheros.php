<?
include_once BASE_DIR ."clases/listados/clase.Listados.php";

class EstTotalTicketsYFicheros extends Action
{
	var $tpl = "tpl/estadisticas/tpl.EstTotalTicketsYFicheros.php";

	function inicializar()
	{
		$this->asignar('options_central', ComboCentral(true, ""));
		$this->asignar('options_tec_central', ComboTecnologiaCentral(true, ""));
		$this->asignar('options_destino', ComboHost(true, ""));
	}

	function buscar()
	{
		// Cargar Datos de la Busqueda
		$this->asignarArray($_GET);

		if($_GET["habilitar_busqueda_tickets"]){
			$listadoTickets = Listados::create('ListadoEstTotalTickets', $_GET);
			$this->asignar("listadoTickets", $listadoTickets->imprimir_listado());
		}

		if($_GET["habilitar_busqueda_ficheros"]){
			$listadoFicheros = Listados::create('ListadoEstTotalFicheros', $_GET);
			$this->asignar("listadoFicheros", $listadoFicheros->imprimir_listado());
		}
	}
	
}
?>