<?php

include_once BASE_DIR . 'clases/negocio/clase.TecnologiaCentral.php';
include_once BASE_DIR . 'clases/dao/dao.TecnologiaCentral.php';


/**
 * @author aamantea
 */
class TecnologiaCentralRemove extends Action {

	function procesar(&$nextAction){
		
		$tecnologiaCentralDAO = new TecnologiaCentralDAO();
		if(!$tecnologiaCentralDAO->sePuedeEliminar($_GET['id_tecnologia_central'])) {
			
			$nextAction->setNextAction("TecnologiaCentralAdmin", "tecnologia.central.eliminar.noSePuede",array(error => "1"));
			
		} else {
			
			$tecnologiaCentral = new TecnologiaCentral($_GET['id_tecnologia_central']);
					
			$tecnologiaCentral->baja_logica = TRUE_;
				
			if ($tecnologiaCentral->save()) {
				$nextAction->setNextAction('TecnologiaCentralAdmin', 'tecnologia.central.eliminar.ok');
			} else {
				$nextAction->setNextAction('TecnologiaCentralAdmin', 'tecnologia.central.eliminar.error',array(error => "1"));
			}
							
		}		
	}
	
}

?>