<?
include_once BASE_DIR."clases/listados/clase.Listados.php";
include_once BASE_DIR."clases/dao/dao.LogAlarma.php";

class SalaMonitor extends Action
{
	var $tpl = "tpl/sala/tpl.SalaMonitor.php";

	function inicializar()
	{			
		$sala = new Sala($_GET['idSala']);
		
		$logRfidDAO = new LogRfidDAO();		
		$guardias = $logRfidDAO->getGuardiaEnSala($sala->id);
		$this->asignar('guardia', $guardias[0]->nombre);
		
		// CARGO LOS DATOS DE LA SALA
		$this->asignar('nombre_sala', $sala->descripcion);				
				
		// CARGO LA LISTA DE MOTAS		
		$params['id_sala'] = $sala->id;		
		$params['tipo'] = CONST_MOTA;		
		$listado_motas = Listados::create("ListadoMotas", $params);
		$this->asignar('listado_motas', $listado_motas->imprimir_listado());

		if($_GET['en_alarma']) $this->asignar('en_alarma',true);
	}
	
	function falsaAlarma(){
		$logAlarmaDAO = new LogAlarmaDAO();		
		$logAlarmaDAO->marcarAlarmasComoFalsas(); 
		$this->asignar('en_alarma','');
	}
	
	function alarmaReal(){
		$logAlarmaDAO = new LogAlarmaDAO();		
		$logAlarmaDAO->marcarAlarmasComoReales(); 
		$this->asignar('en_alarma','');		
	}
}
?>