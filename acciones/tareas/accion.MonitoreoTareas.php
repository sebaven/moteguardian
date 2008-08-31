<?php
include_once BASE_DIR . "clases/dao/dao.Tarea.php";
include_once BASE_DIR . "clases/dao/dao.RecoleccionEjecutada.php";
include_once BASE_DIR . "clases/listados/clase.Listados.php";

class MonitoreoTareas extends Action {
	
	var $tpl = "tpl/tareas/tpl.MonitoreoTareas.php";
	
	function defaults() {	
		$this->buscar(TRUE_);
	}
	
	
	function inicializar() {		
		// Cargo los combos de horas y minutos
		$this->asignar('options_horas_desde',ComboHoras());
		$this->asignar('options_horas_hasta',ComboHoras());
		$this->asignar('options_minutos_desde',ComboMinutos());		
		$this->asignar('options_minutos_hasta',ComboMinutos());		
	}	
	
	
	function validar(&$v) {
		if($_POST['fecha_desde']&&$_POST['fecha_hasta']) {
			$v->add(new Condition(	( $_POST['fecha_hasta'] > $_POST['fecha_desde'] ) ||
									( ($_POST['fecha_hasta'] == $_POST['fecha_desde']) && ($_POST['horas_hasta'].$_POST['minutos_hasta'] > $_POST['horas_desde'].$_POST['minutos_desde']) ) , "monitoreoTareas.rango.inconsistente"), true);

			$hoy = new Fecha();
			$hoy->loadFromNow();			
			$v->add(new Condition($_POST['fecha_desde']<=$hoy->dateToString() ,"monitoreoTareas.fechaPosteriorFechaActual"),true);
		}		
	}
	
	
	function asignarParametrosBusquedaInicial(&$desde, &$hasta, &$soloErroneas, &$nombreRecoleccion) {		
		$fechaDesde = new Fecha();
		$fechaDesde->loadFromYesterday();
		$fechaHasta = new Fecha();
		$fechaHasta->loadFromTomorrow();
		
		$desde = $fechaDesde->dateToString()." 17:00:00";
		$hasta = $fechaHasta->dateToString()." 00:00:00";
		$soloErroneas = TRUE_;
		$nombreRecoleccion = '';	
		
		$this->asignar('id_horas_desde',17);
		$this->asignar('solo_erroneas_checked', 'checked');
		$fechaDesde = new Fecha();
		$fechaDesde->loadFromYesterday();
		$this->asignar('fecha_desde',$fechaDesde->dateToString());
		$fechaHasta = new Fecha();
		$fechaHasta->loadFromTomorrow();
		$this->asignar('fecha_hasta',$fechaHasta->dateToString());
	}
	
	
	function asignarParametrosBusqueda(&$desde, &$hasta, &$soloErroneas, &$nombreRecoleccion) {	
		if($_POST['fecha_desde']) $desde = $_POST['fecha_desde']." ".$_POST['horas_desde'].":".$_POST['minutos_desde'].":00";
		if($_POST['fecha_hasta']) $hasta = $_POST['fecha_hasta']." ".$_POST['horas_hasta'].":".$_POST['minutos_hasta'].":00";
		if($_POST['solo_erroneas']) $soloErroneas = TRUE_;
		if($_POST['nombre_recoleccion']) $nombreRecoleccion = $_POST['nombre_recoleccion'];
	}
	
	
	function buscar($inicial = FALSE_) {
		if($inicial == TRUE_) { // Si se debe realizar la busqueda con los parametros por defecto se cargan los datos correspondientes
			$this->asignarParametrosBusquedaInicial($desde, $hasta, $soloErroneas, $nombreRecoleccion);
		} else { // Sino se cargan los parametros ingresados por el usuario
			$this->asignarParametrosBusqueda($desde, $hasta, $soloErroneas, $nombreRecoleccion);
			$this->actualizarPagina();			
		}
		
		// Traigo las recolecciones que cumplan con los parámetros ingresados
		$daoRecoleccion = new RecoleccionDAO();	
		$recolecciones = $daoRecoleccion->getRecoleccionesMonitoreo($nombreRecoleccion, $desde, $hasta, $soloErroneas);
		
		$this->asignar('cant_recolecciones', count($recolecciones));
				
		for( $iRecoleccion=0 ; $iRecoleccion<count($recolecciones) ; $iRecoleccion++ ) {			
			$this->asignar('recoleccion_'.$iRecoleccion, $recolecciones[$iRecoleccion]->nombre_recoleccion);
			$this->asignar('cant_centrales_'.$iRecoleccion, $recolecciones[$iRecoleccion]->cantidad_centrales);
			
			// Preparo la entrada al nivel de centrales
			$daoCentral = new CentralDAO();
			$centrales = $daoCentral->getCentralesMonitoreo($recolecciones[$iRecoleccion]->id_recoleccion, $soloErroneas);

			// ESTO ES PARA DEBBUG
			if($recolecciones[$iRecoleccion]->cantidad_centrales!=count($centrales)) die("SE ESTÁ CALCULANDO MAL EL NÚMERO DE CENTRALES");
			
			for( $iCentral=0 ; $iCentral<count($centrales) ; $iCentral++ ){
				$this->asignar('central_'.$iRecoleccion."_".$iCentral, $centrales[$iCentral]->nombre_central);
				$this->asignar('cant_ejecuciones_'.$iRecoleccion."_".$iCentral, $centrales[$iCentral]->cantidad_ejecuciones);

				// Preparo a entrada al nivel de ejecuciones
				$daoEjecucion = new RecoleccionEjecutadaDAO();
				$ejecuciones = $daoEjecucion->getEjecucionesMonitoreo($centrales[$iCentral]->id_central, $soloErroneas, $recolecciones[$iRecoleccion]->id_recoleccion);

				// ESTO ES PARA DEBBUG
				if($centrales[$iCentral]->cantidad_ejecuciones!=count($ejecuciones)) die("SE ESTÁ CALCULANDO MAL EL NÚMERO DE EJECUCIONES");
				
				for( $iEjecucion=0 ; $iEjecucion<count($ejecuciones) ; $iEjecucion++ ){
					$this->asignar('cant_archivos_'.$iRecoleccion.'_'.$iCentral.'_'.$iEjecucion, $ejecuciones[$iEjecucion]->cantidad_archivos);
					$this->asignar('fecha_inicio_'.$iRecoleccion."_".$iCentral."_".$iEjecucion, $ejecuciones[$iEjecucion]->fecha_inicio);
					$this->asignar('log_'.$iRecoleccion."_".$iCentral."_".$iEjecucion, BASE_DIR."batch/log/nucleo_srdf_".$ejecuciones[$iEjecucion]->pid.".log");

					// Preparo la entrada al nivel de archivos
					$daoTarea = new TareaDAO();
					$tareas = $daoTarea->getTareasMonitoreo($ejecuciones[$iEjecucion]->id_ejecucion, $centrales[$iCentral]->id_central ,$soloErroneas);
					
					// ESTO ES PARA DEBBUG				
					if($ejecuciones[$iEjecucion]->cantidad_archivos!=count($tareas)) die("SE ESTÁ CALCULANDO MAL EL NÚMERO DE ARCHIVOS");					
					
					for( $iArchivo=0 ; $iArchivo<count($tareas) ; $iArchivo++ ){
						$this->asignar('nombre_original_'.$iRecoleccion.'_'.$iCentral.'_'.$iEjecucion.'_'.$iArchivo, $tareas[$iArchivo]->nombre_original);											
						$this->asignar('ubicacion_archivo_'.$iRecoleccion.'_'.$iCentral.'_'.$iEjecucion.'_'.$iArchivo, $daoTarea->getPathArchivoValidado($tareas[$iArchivo]->id_tarea));
						
						$listadoEstados = Listados::create("ListadoTareas", $tareas[$iArchivo]->id_tarea);
						$this->asignar('listado_estados_ejecutados_'.$iRecoleccion.'_'.$iCentral.'_'.$iEjecucion.'_'.$iArchivo, $listadoEstados->imprimir_listado());	
						
						$this->asignar('imagen_estado_'.$iRecoleccion.'_'.$iCentral.'_'.$iEjecucion.'_'.$iArchivo, $this->imagenPeorEstado($listadoEstados->imprimir_listado()));
					}																
				}				
			}
		}
	}
	
	
	//TODO: hacerlo de una forma mas elegante :S	
	function imagenPeorEstado($listado){		
		if(strpos($listado,'true')!=false){			
			return 'imagenes/listado/exitoTarea/true.png';
		} else if(strpos($listado,'null')!=false){
			return 'imagenes/listado/exitoTarea/null.png'; 
		} else if(strpos($listado,'false')!=false) {
			return 'imagenes/listado/exitoTarea/false.png';
		}
	}
	
	
	function actualizarPagina() {
		$this->asignar('nombre_recoleccion', $_POST['nombre_recoleccion']);
		$this->asignar('fecha_desde', $_POST['fecha_desde']);
		$this->asignar('fecha_hasta', $_POST['fecha_hasta']);
		$this->asignar('id_horas_desde', $_POST['horas_desde']);
		$this->asignar('id_horas_hasta', $_POST['horas_hasta']);
		$this->asignar('id_minutos_desde', $_POST['minutos_desde']);
		$this->asignar('id_minutos_hasta', $_POST['minutos_hasta']);
		if($_POST['solo_erroneas']) $this->asignar('solo_erroneas_checked','checked');		
	}
}

?>