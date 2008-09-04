<?
include_once BASE_DIR."clases/listados/clase.Listados.php";

class EstadisticaRondaRealizada extends Action
{
	var $tpl = "tpl/estadistica/tpl.EstadisticaRondaRealizada.php";

    function inicializar()
    {

    }

    
    function buscar()
    {
        // Recargo los datos en pantalla para que se sepa que busqué
        $this->asignarArray($_GET);

        $listado = Listados::create('ListadoRondasRealizadas', $_GET);
        $this->asignar("listado", $listado->imprimir_listado());
    }    	
}
?>