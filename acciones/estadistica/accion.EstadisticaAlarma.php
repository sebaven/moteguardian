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
    }

    
    function buscar()
    {
        // Recargo los datos en pantalla para que se sepa que busqué
        $this->asignarArray($_GET);

        $logAlarma = new LogAlarmaDAO();
        $this->asignar('visibility', 'visible');
        $this->asignar('reales', $logAlarma->getAlarmasReales($_GET));
        $this->asignar('falsas', $logAlarma->getFalsasAlarmas($_GET));
        
        $listado = Listados::create('ListadoAlarmas', $_GET);
        $this->asignar("listado", $listado->imprimir_listado());
    }    	
}
?>