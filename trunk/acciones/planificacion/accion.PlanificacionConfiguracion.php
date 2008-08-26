<?php
include_once BASE_DIR ."clases/negocio/clase.Planificacion.php";
include_once(BASE_DIR."comun/Fecha.php");

/**
 * @author cGalli
 * @author mHernandez
 */
class PlanificacionConfiguracion extends Action
{
	var $tpl = "tpl/planificacion/tpl.PlanificacionConfiguracion.php";

	function inicializar() {

		if($_GET['id']){
			$planificacion = new Planificacion($_GET['id']);
			$hora = explode(":",$planificacion->hora);

			if(isset($planificacion->dia_absoluto)) {
				$this->asignar('fecha', $planificacion->dia_absoluto);
				$this->asignar('id_hora_absoluto', $hora[0]);
				$this->asignar('id_minutos_absoluto', $hora[1]);
				$this->asignar('absoluto_checked',"checked");
			}else if(isset($planificacion->dia_semana)) {
				$this->asignar('id_dia', $planificacion->dia_semana);
				$this->asignar('id_hora_semanalmente', $hora[0]);
				$this->asignar('id_minutos_semanalmente', $hora[1]);
				$this->asignar('semanalmente_checked',"checked");
			}else{
				$this->asignar('id_hora_diariamente', $hora[0]);
				$this->asignar('id_minutos_diariamente', $hora[1]);
				$this->asignar('diariamente_checked',"checked");
			}
			$this->asignar('id', $_GET['id']);
			$this->asignar('id_actividad', $_GET['id_actividad']);
			$this->asignar('id_actividad_envio', $_GET['id_actividad_envio']);
		}
		if($_POST['radioFrecuencia']){
			$this->asignar('fecha', $_POST['fecha']);
			$this->asignar('id_hora_absoluto', $_POST['hora_absoluto']);
			$this->asignar('id_minutos_absoluto', $_POST['minutos_absoluto']);
			$this->asignar('id_dia', $_POST['dia']);
			$this->asignar('id_hora_semanalmente', $_POST['hora_semanalmente']);
			$this->asignar('id_minutos_semanalmente', $_POST['minutos_semanalmente']);
			$this->asignar('id_hora_diariamente', $_POST['hora_diariamente']);
			$this->asignar('id_minutos_diariamente', $_POST['minutos_diariamente']);
			$this->asignar('id', $_POST['id']);
			$this->asignar('id_actividad', $_POST['id_actividad']);
			$this->asignar('id_actividad_envio', $_POST['id_actividad_envio']);
			if($_POST['radioFrecuencia']=="absoluto") {
				$this->asignar('absoluto_checked',"checked");
			} else if($_POST['radioFrecuencia']=="diariamente") {
				$this->asignar('diariamente_checked',"checked");
			} else if($_POST['radioFrecuencia']=="semanalmente") {
				$this->asignar('semanalmente_checked',"checked");
			}
			
		}
		
		$this->asignar('options_hora_absoluto', comboHoras());
		$this->asignar('options_hora_diariamente', comboHoras());
		$this->asignar('options_hora_semanalmente', comboHoras());
		$this->asignar('options_minutos_absoluto', comboMinutos());
		$this->asignar('options_minutos_diariamente', comboMinutos());
		$this->asignar('options_minutos_semanalmente', comboMinutos());
		$this->asignar('options_dia', comboDias());


	}

	function validar(&$v){
		$v->add(new Required('radioFrecuencia', 'planificacion.radioFrecuenciaRequiered'));
		if($_POST["radioFrecuencia"]=='absoluto'){
			$v->add(new Required('fecha', 'planificacion.fechaRequiered'));
			
			$fecha = new Fecha();
			$fecha->loadFromNow();
			$fecha_str = $fecha->dateToString();
			
			$v->add(new Condition($_POST['fecha'] > $fecha_str, 'planificacion.fechaVieja'));	
		}
		if($_POST['radioFrecuencia']=='semanalmente') {
			$v->add(new Required('dia', 'planificacion.diaRequiered'));
		}		
	}

	function procesar(&$nextAction){

		$planificacion = new Planificacion();
		$planificacion->baja_logica = FALSE_;
		
		if( isset($_GET["id"]) ){
			$planificacion->id = $_GET['id'];
		}

		if ( isset($_GET["id_actividad"]) ){
			$planificacion->id_actividad = $_GET["id_actividad"];
			$planificacion->id_actividad_envio = "NULL";
				
		}else if ( isset($_GET["id_actividad_envio"]) ){
			$planificacion->id_actividad_envio = $_GET["id_actividad_envio"];
			$planificacion->id_actividad = "NULL";
		}
			
		if($_POST["radioFrecuencia"]=='absoluto') {
			$planificacion->hora = $_POST["hora_absoluto"].":".$_POST["minutos_absoluto"];			
			$planificacion->dia_absoluto = $_POST['fecha']; 			
			$planificacion->dia_semana = "NULL";				
		}else if($_POST['radioFrecuencia']=="semanalmente") {
			$planificacion->hora = $_POST["hora_semanalmente"].":".$_POST["minutos_semanalmente"];
			$planificacion->dia_absoluto = "NULL";
			$planificacion->dia_semana = $_POST["dia"];				
		}else{
			$planificacion->hora = $_POST["hora_diariamente"].":".$_POST["minutos_diariamente"].":00";
			$planificacion->dia_absoluto = "NULL";
			$planificacion->dia_semana = "NULL";

		}

		$params['pop']=1;
		
		if($planificacion->save()) {			
			$nextAction->setNextAction('SalidaPopupPlanificacion', 'planificacion.configuracion.ok', $params);
		} else {
			$nextAction->setNextAction('SalidaPopupPlanificacion', 'planificacion.configuracion.error', $params);
		}



	}
}
?>