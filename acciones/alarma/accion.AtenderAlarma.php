<?
include_once BASE_DIR."clases/dao/dao.LogAlarma.php";

class AtenderAlarma extends Action {
	
	function inicializar(){				
		// redireccionar 
		$this->procesar();		
	}	
	
	function procesar(&$nextAction)
    {
    	$logAlarmaDAO = new LogAlarmaDAO();
		$id_sala = $logAlarmaDAO->getSalaAlarmaIniciada();
		
        $nextAction->setNextAction('SalaMonitor', '', array('en_alarma'=>TRUE_, 'idSala'=>$id_sala) );
    }
}

?>