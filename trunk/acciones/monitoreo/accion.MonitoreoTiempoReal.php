<?
include_once BASE_DIR ."clases/listados/clase.Listados.php";

class MonitoreoTiempoReal extends Action
{
	var $tpl = "tpl/monitoreo/tpl.MonitoreoTiempoReal.php";

	function inicializar()
	{
		$listadoRecolecciones = Listados::create('ListadoMonitoreoRecoleccionTiempoReal');
		$this->asignar("listadoRecolecciones", $listadoRecolecciones->imprimir_listado());
		
		$listadoEnvios = Listados::create('ListadoMonitoreoEnvioTiempoReal');
		$this->asignar("listadoEnvios", $listadoEnvios->imprimir_listado());
	}

	function buscar()
	{
		// Cargar Datos de la Busqueda
		$this->asignarArray($_GET);

		$listadoRecolecciones = Listados::create('ListadoMonitoreoRecoleccionTiempoReal', $_GET);
		$this->asignar("listadoRecolecciones", $listadoRecolecciones->imprimir_listado());
		
		$listadoEnvios = Listados::create('ListadoMonitoreoEnvioTiempoReal', $_GET);
		$this->asignar("listadoEnvios", $listadoEnvios->imprimir_listado());
	}
	
}
?>