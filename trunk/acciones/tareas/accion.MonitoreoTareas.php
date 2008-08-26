<?php
include_once BASE_DIR . "clases/dao/dao.ActividadEjecutada.php";
include_once BASE_DIR . "clases/dao/dao.Tarea.php";
include_once BASE_DIR . "clases/listados/clase.Listados.php";

class MonitoreoTareas extends Action {
	
	var $tpl = "tpl/tareas/tpl.MonitoreoTareas.php";
	
	function defaults() {
		// Se cargan los datos de busqueda iniciales
		$this->asignar('solo_erroneas_checked', 'checked');
		$fechaDesde = new Fecha();
		$fechaDesde->loadFromYesterday();
		$this->asignar('fecha_desde',$fechaDesde->dateToString());
		$fechaHasta = new Fecha();
		$fechaHasta->loadFromTomorrow();
		$this->asignar('fecha_hasta',$fechaHasta->dateToString());
		
		// Se realiza una bï¿½squeda con los parï¿½metros por defecto
		$this->buscar(TRUE_);
	}
	function inicializar() {		
		// Cargo los combos de horas y minutos
		$this->asignar('options_horas_desde',ComboHoras());
		$this->asignar('options_horas_hasta',ComboHoras());
		$this->asignar('options_minutos_desde',ComboMinutos());		
		$this->asignar('options_minutos_hasta',ComboMinutos());	
		
	}	
	
	
	function asignarParametrosBusquedaInicial(&$desde, &$hasta, &$soloErroneas, &$nombreActividad) {
		$nombreActividad = '';	
		$fechaDesde = new Fecha();
		$fechaDesde->loadFromYesterday();
		$fechaHasta = new Fecha();
		$fechaHasta->loadFromTomorrow();
		//TODO : Por ahora no se utiliza las horas ni los minutos
		$desde = $fechaDesde->dateToString()." 17:00:00";
		$hasta = $fechaHasta->dateToString()." 00:00:00";
		$soloErroneas = TRUE_;
		//Hardcodeamos la hora desde a las 17 porque lo pidió Diego
		$this->asignar('id_horas_desde',17);
	}
	
	function asignarParametrosBusqueda(&$desde, &$hasta, &$soloErroneas, &$nombreActividad) {	
		if($_POST['fecha_desde']) $desde=$_POST['fecha_desde']." ".$_POST['horas_desde'].":".$_POST['minutos_desde'].":00";
		if($_POST['fecha_hasta']) $hasta=$_POST['fecha_hasta']." ".$_POST['horas_hasta'].":".$_POST['minutos_hasta'].":00";
		if($_POST['solo_erroneas']) $soloErroneas = TRUE_;
		if($_POST['nombre_actividad']) $nombreActividad = $_POST['nombre_actividad'];
	}
	
	
	function buscar($inicial = FALSE_) {
		if($inicial == TRUE_) {
			// Si se debe realizar la busqueda con los parï¿½metros por defecto se cargan los datos correspondientes
			$this->asignarParametrosBusquedaInicial($desde,$hasta,$soloErroneas,$nombreActividad);
		} else {
			// Sino se cargan los parï¿½metros ingresados por el usuario
			$this->asignarParametrosBusqueda($desde,$hasta,$soloErroneas,$nombreActividad);
		}
		
		// Traigo las actividades que cumplan con los parï¿½metros de bï¿½squeda
		$actividadEjecutadaDAO = new ActividadEjecutadaDAO();	
		$actividades = $actividadEjecutadaDAO->getCantidadArchivosPorEjecucion($nombreActividad,$desde,$hasta,$soloErroneas);
		
		// Si se ejecutï¿½ una bï¿½squeda distina a la inicial, vuelvo a cargar los criterios de bï¿½squeda que se ingresaron
		if($inicial == FALSE_) {
			$this->asignar('nombre_actividad', $_POST['nombre_actividad']);
			$this->asignar('fecha_desde', $_POST['fecha_desde']);
			$this->asignar('fecha_hasta', $_POST['fecha_hasta']);
			$this->asignar('id_horas_desde', $_POST['horas_desde']);
			$this->asignar('id_horas_hasta', $_POST['horas_hasta']);
			$this->asignar('id_minutos_desde', $_POST['minutos_desde']);
			$this->asignar('id_minutos_hasta', $_POST['minutos_hasta']);
			if($_POST['solo_erroneas']) $this->asignar('solo_erroneas_checked','checked');	
		}
		
		$iRow = 0;
		$iActividad = 0;
		while($actividades['nombre_actividad'][$iRow])
		{						
			// Para cada actividad			
			$this->asignar('actividad_'.$iActividad, $actividades['nombre_actividad'][$iRow]);			
			$iEjecucion = 0; 
			$fechaEjecucion = $actividades['fecha_ejecucion'][$iRow];
			$idEjecucion = $actividades['id'][$iRow];						
			while($actividades['id'][$iRow] == $idEjecucion) 
			{
				// Para cada ejecuciï¿½n
				$this->asignar("cant_archivos_".$iActividad.'_'.$iEjecucion, $actividades['cant_archivos'][$iRow]);
				$this->asignar("fecha_inicio_".$iActividad.'_'.$iEjecucion, $fechaEjecucion);
				$tareaDAO = new TareaDAO();
				
				if($soloErroneas) {
					$archivos = $tareaDAO->getTareasByIdActividadEjecutada($actividades['id'][$iRow], TRUE_ );					
				} else {
					$archivos = $tareaDAO->getTareasByIdActividadEjecutada($actividades['id'][$iRow], FALSE_ );
				}
								
				for( $iArchivo=0 ; $iArchivo<$actividades['cant_archivos'][$iRow] ; $iArchivo++ ) {																			
					$listado = Listados::create('ListadoTareas', $archivos['id'][$iArchivo] );
					$listado->escapear_html = false;		
					$this->asignar('listado_estados_ejecutados_'.$iActividad.'_'.$iEjecucion.'_'.$iArchivo, $listado->imprimir_listado());									
				}							
				$iRow++;
				$iEjecucion++;
			}
			$this->asignar('cant_ejecuciones_'.$iActividad, $iEjecucion);
			$iActividad++;			
		}
		if($actividades['nombre_actividad'][0]) {			
			$this->asignar('cant_actividades', $iActividad);
		} else {
			$this->asignar('cant_actividades', 0);
		}			
	}
}

?>