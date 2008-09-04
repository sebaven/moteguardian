<?
include_once BASE_DIR."clases/listados/clase.Listados.php";
include_once BASE_DIR."clases/dao/dao.Planificacion.php";

class RondaAdm extends Action
{
	var $tpl = "tpl/ronda/tpl.RondaAdm.php";

	function inicializar()
	{
        // Cargo los combos
		$this->asignar('options_guardias', ComboGuardia());
		$this->asignar('options_horas_desde',ComboHoras());
		$this->asignar('options_horas_hasta',ComboHoras());
		$this->asignar('options_minutos_desde',ComboMinutos());		
		$this->asignar('options_minutos_hasta',ComboMinutos());	
		        
	}

	
	function buscar()
	{
		$desde = new Fecha();
		$hasta = new Fecha();
		
		$desde->loadFromString($_GET['fecha_desde']." ".$_GET['horas_desde'].":".$_GET['minutos_desde'].":00");
		$hasta->loadFromString($_GET['fecha_hasta']." ".$_GET['horas_hasta'].":".$_GET['minutos_hasta'].":00");
		
		$planificacionDAO = new PlanificacionDAO();
		$planificaciones = $planificacionDAO->filterByFecha($desde, $hasta);
		
		if(count($planificaciones)>0){
			foreach( $planificaciones as $i=>$planificacion ){
				$params['planificaciones'][$i] = $planificacion->id;							
			}								
		} 
		$params['id_guardia'] = $_GET['id_guardia'];
		
		$this->actualizarPantalla();
		
		$listado = Listados::create('ListadoRonda', $params);
		$this->asignar("listado", $listado->imprimir_listado());
	}

	
	function actualizarPantalla() {
		$this->asignar('id_guardia', $_GET['id_guardia']);
		$this->asignar('fecha_desde', $_GET['fecha_desde']);
		$this->asignar('fecha_hasta', $_GET['fecha_hasta']);
		$this->asignar('id_horas_desde', $_GET['horas_desde']);
		$this->asignar('id_horas_hasta', $_GET['horas_hasta']);
		$this->asignar('id_minutos_desde', $_GET['minutos_desde']);
		$this->asignar('id_minutos_hasta', $_GET['minutos_hasta']);				
	}
}
?>