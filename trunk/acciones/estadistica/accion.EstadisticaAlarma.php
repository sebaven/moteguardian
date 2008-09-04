<?
include_once "clases/dao/dao.LogAlarma.php";
include_once "clases/listados/clase.Listados.php";

class EstadisticaAlarma extends Action
{
	var $tpl = "tpl/estadistica/tpl.EstadisticaAlarma.php";

    function inicializar()
    {
        $logAlarma = new LogAlarmaDAO();
        
        $this->asignar('visibility', 'hidden');
         // Cargo los combos
		$this->asignar('options_guardias', ComboGuardia());
		$this->asignar('options_horas_desde',ComboHoras());
		$this->asignar('options_horas_hasta',ComboHoras());
		$this->asignar('options_minutos_desde',ComboMinutos());		
		$this->asignar('options_minutos_hasta',ComboMinutos());	
    }

    
    function buscar()
    {
        // Recargo los datos en pantalla para que se sepa que busqué
        $param['desde'] = $_GET['fecha_desde']." ".$_GET['horas_desde'].":".$_GET['minutos_desde'].":00";
        $param['hasta'] = $_GET['fecha_hasta']." ".$_GET['horas_hasta'].":".$_GET['minutos_hasta'].":00";
    	
        $logAlarma = new LogAlarmaDAO();
        $this->asignar('visibility', 'visible');
        $this->asignar('reales', $logAlarma->getAlarmasReales($param));
        $this->asignar('falsas', $logAlarma->getFalsasAlarmas($param));   
                        
        $listado = Listados::create('ListadoAlarmas', $param);
        $this->asignar("listado", $listado->imprimir_listado());
        
        $this->actualizarPantalla();
    }   

	function actualizarPantalla() {
		$this->asignar('fecha_desde', $_GET['fecha_desde']);
		$this->asignar('fecha_hasta', $_GET['fecha_hasta']);
		$this->asignar('id_horas_desde', $_GET['horas_desde']);
		$this->asignar('id_horas_hasta', $_GET['horas_hasta']);
		$this->asignar('id_minutos_desde', $_GET['minutos_desde']);
		$this->asignar('id_minutos_hasta', $_GET['minutos_hasta']);				
	}
}
?>