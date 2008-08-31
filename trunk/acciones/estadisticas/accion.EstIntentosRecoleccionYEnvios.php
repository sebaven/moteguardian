<?
include_once BASE_DIR ."clases/listados/clase.Listados.php";

class EstIntentosRecoleccionYEnvios extends Action
{
	var $tpl = "tpl/estadisticas/tpl.EstIntentosRecoleccionYEnvios.php";

	function inicializar()
	{
		$this->asignar('options_tec_enviador',ComboTecnologiaEnviador(true));
		$this->asignar('options_tec_recolector',ComboTecnologiaRecoleccion(true));
	}

	function buscar()
	{
		// Cargar Datos de la Busqueda
		$this->asignarArray($_GET);

		if($_GET["habilitar_busqueda_recoleccion"]){
			$listadoRecolecciones = Listados::create('ListadoEstIntentosRecoleccion', $_GET);
			$this->asignar("listadoRecolecciones", $listadoRecolecciones->imprimir_listado());
		}

		if($_GET["habilitar_busqueda_envio"]){
			$listadoRecolecciones = Listados::create('ListadoEstIntentosEnvio', $_GET);
			$this->asignar("listadoEnvios", $listadoRecolecciones->imprimir_listado());
		}
	}
	
}
?>