<?php
include_once BASE_DIR . "clases/listados/clase.Listados.php";

class AdministracionDeRecoleccionesYEnviosEnCurso extends Action {
	
	var $tpl = "tpl/actividad/tpl.RecoleccionesYEnviosEnCurso.php";
	
	function defaults(){
		$fechaDesde = new Fecha();
		$fechaDesde->loadFromYesterday();
		$this->asignar('fecha_desde',$fechaDesde->dateToString());
		
		$fechaHasta = new Fecha();
		$fechaHasta->loadFromTomorrow();
		$this->asignar('fecha_hasta',$fechaHasta->dateToString());
	}
	
	
	function inicializar() {	
		$this->asignar('options_horas_desde',ComboHoras());
		$this->asignar('options_horas_hasta',ComboHoras());
		
		$this->asignar('options_minutos_desde',ComboMinutos());		
		$this->asignar('options_minutos_hasta',ComboMinutos());
	}
	
	
	function buscar(){
		if($_GET['recolecciones_con_fallas']) {
			$listado_recolecciones = Listados::create("ListadoRecoleccionesConFallas", $_GET);
			$this->asignar("listado_recolecciones_con_fallas", $listado_recolecciones->imprimir_listado());
		}
		if($_GET['envios_con_fallas']){
			$listado_envios = Listados::create("ListadoEnviosConFallas", $_GET);		
			$this->asignar("listado_envios_con_fallas", $listado_envios->imprimir_listado());
		}

		$this->actualizarPagina();
	}
	
	
	function limpiar(){
		
	}
	
	
	function actualizarPagina() {
		$this->asignar("nombre", trim($_GET['nombre']));
		$this->asignar("nombre_de_fichero", trim($_GET['nombre_de_fichero']));
		
		$this->asignar("fecha_desde", trim($_GET['fecha_desde']));
		$this->asignar("id_horas_desde", $_GET['id_horas_desde']);
		$this->asignar("id_minutos_desde", $_GET['id_minutos_desde']);
		
		$this->asignar("fecha_hasta", trim($_GET['fecha_hasta']));
		$this->asignar("id_horas_hasta", $_GET['id_horas_hasta']);
		$this->asignar("id_minutos_hasta", $_GET['id_minutos_hasta']);
		
		if($_GET['recolecciones_con_fallas']) $this->asignar("recolecciones_con_fallas_checked", "checked");
		if($_GET['envios_con_fallas']) $this->asignar("envios_con_fallas_checked", "checked");
	}
	
}
?>