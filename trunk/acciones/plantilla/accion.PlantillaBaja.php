<?
include_once BASE_DIR ."clases/negocio/clase.Plantilla.php";
include_once BASE_DIR."clases/dao/dao.Actividad.php";

class PlantillaBaja extends Action {
	var $tpl = "";
	
	function procesar(&$nextAction){
		$actividadDAO = new ActividadDAO();
		if($actividadDAO->filterByField('id_plantilla', $_GET['id'])) {
			$nextAction->setNextAction("PlantillaAdmin", "plantilla.eliminacion.error.tiene.actividad");
		} else {
			$plantilla = new Plantilla($_GET['id']);			
			$plantilla->baja_logica = TRUE_;
						
			if ($plantilla->update()) {
				$nextAction->setNextAction("PlantillaAdmin", "plantilla.eliminacion.ok");			
			}
			else {
				$nextAction->setNextAction("PlantillaAdmin", "plantilla.eliminacion.error",array(error => "1"));	
			}			
		}
		
	}
}
?>