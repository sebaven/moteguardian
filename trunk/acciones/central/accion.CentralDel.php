<?php

include_once BASE_DIR . 'clases/negocio/clase.Central.php';
include_once BASE_DIR . "clases/listados/clase.Listados.php";
/**
 * @author aamantea
 */
class CentralDel extends Action {

	function procesar(&$nextAction){
		$central = new Central($_GET['id']);
		$actividadDAO = new ActividadDAO();
		$actividades = $actividadDAO->filterByField("id_central",$central->id);
		
		//Si no había ninguna actividad asociada a la central, se puede borrar
		if(!isset($actividades[0])) {
			$central->baja_logica = TRUE_;
			$res = $central->update($_GET['id']);	
		}
		
		if ($res) {
			$nextAction->setNextAction('CentralBuscar', 'central.eliminar.ok');
		} else {
			$nextAction->setNextAction('CentralBuscar', 'central.eliminar.error');
		}
	}
	
}

?>