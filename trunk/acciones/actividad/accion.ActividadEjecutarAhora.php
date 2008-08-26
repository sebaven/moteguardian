<?php

include_once BASE_DIR . 'clases/negocio/clase.Central.php';
include_once BASE_DIR . "clases/listados/clase.Listados.php";
include_once BASE_DIR . "clases/negocio/clase.Planificacion.php";
/**
 * @author mhernandez
 */
class ActividadEjecutarAhora extends Action {

	function procesar(&$nextAction){
			
		//Se crea una fecha y se obtiene la actual
		$fecha = new Fecha();
		$fecha->loadFromNow();
		//Se le suma x segundos a la hora para q la planificacion se realice en el prox. ciclo del dispacher.
		$fecha->addSeconds(30); //FIXME: sacar el dato de la configutacion del dispacher
						
		//Creo planificacion y agrego sus datos correspondientes.
		$planificacion = new Planificacion();
		$planificacion->id_actividad = $_GET['id'];
		$planificacion->hora = $fecha->hora.":".$fecha->minutos.":".$fecha->segundos;			
		$planificacion->dia_absoluto = $fecha->anio."-".$fecha->mes."-".$fecha->dia; 			
		$planificacion->dia_semana = "NULL";	
		$planificacion->id_actividad_envio = "NULL";
		$planificacion->baja_logica = FALSE_;
		
		$parametros['id'] = $_GET['id'];
		
		$res = $planificacion->save();
		
		if ($res) {
			$nextAction->setNextAction('ActividadAdministracion', 'actividad.ejecutarAhora.ok', $parametros);
		}
		else {
			$nextAction->setNextAction('ActividadAdministracion', 'actividad.ejecutarAhora.error', $parametros);
		}
	}
	
}

?>