<?php

include_once BASE_DIR . "clases/negocio/clase.Planificacion.php";
/**
 * @author mhernandez
 */
class RecoleccionEjecutarAhora extends Action {

	function procesar(&$nextAction){
			
		//Se crea una fecha y se obtiene la actual
		$fecha = new Fecha();
		$fecha->loadFromNow();
		//Se le suma x segundos a la hora para q la planificacion se realice en el prox. ciclo del dispacher.
		$fecha->addSeconds(30); //FIXME: sacar el dato de la configutacion del dispacher
						
		//Creo planificacion y agrego sus datos correspondientes.
		$planificacion = new Planificacion();
		$planificacion->id_recoleccion = $_GET['id_recoleccion'];
		$planificacion->hora = $fecha->hora.":".$fecha->minutos.":".$fecha->segundos;			
		$planificacion->dia_absoluto = $fecha->anio."-".$fecha->mes."-".$fecha->dia; 			
		$planificacion->dia_semana = "NULL";	
		$planificacion->id_actividad_envio = "NULL";
		$planificacion->baja_logica = FALSE_;
		$planificacion->fecha_vigencia = $fecha->anio."-".$fecha->mes."-".$fecha->dia;		
		
		$recoleccion = new Recoleccion($_GET['id_recoleccion']);
		$recoleccion->habilitado = TRUE_;
				
		$parametros['id_recoleccion'] = $_GET['id_recoleccion'];
		
		$res = $planificacion->save() && $recoleccion->save();
		
		if ($res) {
			$nextAction->setNextAction('RecoleccionConf', 'recoleccion.ejecutarAhora.ok', $parametros);
		}
		else {
			$parametros['error'] = "1";
			$nextAction->setNextAction('RecoleccionConf', 'recoleccion.ejecutarAhora.error', $parametros);
		}
	}
	
}

?>