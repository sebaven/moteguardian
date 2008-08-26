<?php
include_once BASE_DIR ."clases/negocio/clase.NodoPlantilla.php";
include_once BASE_DIR ."clases/dao/dao.NodoPlantilla.php";

/**
 * @author cgalli
 *
 */
class NodoPlantillaRemove extends Action  
{
	var $tpl = "";
	
	function procesar(&$nextAction){
		$nodoPlantillaDAO = new NodoPlantillaDAO();
		
		$npBorrado = new NodoPlantilla($_GET['id']);
		$npSiguiente = $nodoPlantillaDAO->getNodoSiguiente($_GET['id']);
				
		if($npSiguiente) {
			$npSiguiente->id_nodo_plantilla_anterior = ($npBorrado->id_nodo_plantilla_anterior == "") ? 'NULL' : $npBorrado->id_nodo_plantilla_anterior;
			$npSiguiente->save();
		}
		
		$parametros['id_plantilla'] = $_GET['id_plantilla'];
		$parametros['nombre_plantilla'] = $_GET['nombre_plantilla'];
		$parametros['id_tipo_filtro'] = $_GET['id_tipo_filtro'];

		$npBorrado->baja_logica = TRUE_;
		
		if($npBorrado->save()) {
			$nextAction->setNextAction("PlantillaConfiguracion", "eliminacion.filtro.ok", $parametros);
		}
		else {
			$nextAction->setNextAction("PlantillaConfiguracion", "eliminacion.filtro.error", $parametros);	
		}
	}
}

?>