<?php
include_once BASE_DIR . 'clases/negocio/clase.Tarea.php';

class ActividadEnvioDel extends Action
{
	
	function procesar(&$nextAction){
		$tarea = new Tarea($_GET['id_tarea']);
		$tarea->baja_logica = TRUE_;
		$tarea->esperando_envio = FALSE_;
		$tarea->esperando_recoleccion = FALSE_;				
		$res = $tarea->save();		
		if ($res) {
			$nextAction->setNextAction('AdministracionDeRecoleccionesYEnviosEnCurso', 'cancelar.tarea.ok');
		}
		else {
			$parametros['error'] = "1";
			$nextAction->setNextAction('AdministracionDeRecoleccionesYEnviosEnCurso', 'cancelar.tarea.error', $parametros);
		}
	}
	
}
?>