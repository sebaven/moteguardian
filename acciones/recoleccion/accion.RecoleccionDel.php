<?php

include_once BASE_DIR . 'clases/negocio/clase.Recoleccion.php';
include_once BASE_DIR ."clases/listados/clase.Listados.php";

class RecoleccionDel extends Action {
	
	function procesar(&$nextAction){
		$recoleccion = new Recoleccion($_GET['id']);		

		if($recoleccion->id_actividad) {
			$actividad = new Actividad($recoleccion->id_actividad);
			if($actividad->baja_logica==FALSE_ && $actividad->temporal==FALSE_){				
				$nextAction->setNextAction('RecoleccionAdmin', 'recoleccion.eliminacion.asociada.a.actividad',array(error => "1"));
				return;	
			}
		}
		
		$envioDAO = new EnvioDAO();
		$envios = $envioDAO->getByIdRecoleccion($recoleccion->id);
		if(count($envios)!=0){
			$nextAction->setNextAction('RecoleccionAdmin', 'recoleccion.eliminacion.asociada.a.envio',array(error => "1"));			
			return;
		}
		
		$recoleccion->baja_logica = TRUE_;
		$res = $recoleccion->save();	
		
		if ($res) {
			$nextAction->setNextAction('RecoleccionAdmin', 'recoleccion.eliminacion.ok');
		}
		else {
			$nextAction->setNextAction('RecoleccionAdmin', 'recoleccion.eliminacion.error',array(error => "1"));
		}
	}
	
}


?>