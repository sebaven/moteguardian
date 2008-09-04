<?php
include_once BASE_DIR ."clases/negocio/clase.ItemRonda.php";
include_once BASE_DIR."clases/dao/dao.ItemRonda.php";

class ItemRondaConfiguracion extends Action
{
	var $tpl = "tpl/item_ronda/tpl.ItemRondaConfiguracion.php";

	function inicializar() 
	{
		if($_GET['id']){
			$itemRonda = new ItemRonda($_GET['id']);
						
			$id_horas = floor($itemRonda->duracion / 60);
			$id_minutos = $itemRonda->duracion % 60;
						
			$this->asignar('orden', $itemRonda->orden);					
			$this->asignar('id_sala', $itemRonda->id_sala);					
			$this->asignar('id_horas',$id_horas);
			$this->asignar('id_minutos',$id_minutos);
		}		
		
		$this->asignar('options_horas', comboHoras());
		$this->asignar('options_minutos', comboMinutos());		
		$this->asignar('options_salas', ComboSala());		
	}

	
	function procesar(&$nextAction)
	{
		if($_GET["id"]){
			$itemRonda = new ItemRonda($_GET['id']);
		} else {
			$itemRonda = new ItemRonda();
		}

		$itemRonda->id_ronda = $_GET["id_ronda"];				
		$itemRonda->baja_logica = FALSE_;
				
		$itemRonda->duracion = $_POST['id_horas'] * 60 + $_POST['id_minutos'];
		$itemRonda->id_sala = $_POST['id_sala'];
		$itemRonda->orden = $_POST['orden'];		 

		$params['pop']=1;
		
		if($itemRonda->save()) {			
			$nextAction->setNextAction('SalidaPopupItemRonda', 'item.ronda.configuracion.ok', $params);
		} else {
			$params['error'] = "1";
			$nextAction->setNextAction('SalidaPopupItemRonda', 'item.ronda.configuracion.error', $params);
		}



	}
}
?>