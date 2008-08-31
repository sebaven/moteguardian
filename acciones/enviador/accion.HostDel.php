<?php

include_once BASE_DIR . 'clases/negocio/clase.Host.php';
include_once BASE_DIR . 'clases/dao/dao.HostEnvio.php';
include_once BASE_DIR . "clases/listados/clase.Listados.php";

/**
 * @author aamantea
 */
class HostDel extends Action {

	function procesar(&$nextAction){
		$host = new Host($_GET['id']);
		
		$hostEnvioDAO = new HostEnvioDAO();
		$hostEnvios = $hostEnvioDAO->filterByField("id_host",$host->id);
		
		// Si no había ningun envío asociado al host, se puede borrar
		if(!isset($hostEnvios[0])) {
			$host->baja_logica = TRUE_;
			$res = $host->update($_GET['id']);	
		}
		
		if ($res) {
			$nextAction->setNextAction('HostBuscar', 'host.eliminar.ok');
		} else {
			$nextAction->setNextAction('HostBuscar', 'host.eliminar.error',array(error => "1"));
		}
	}
	
}

?>