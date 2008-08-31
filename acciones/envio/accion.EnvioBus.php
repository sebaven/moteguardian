<?
include_once BASE_DIR ."clases/listados/clase.Listados.php";

class EnvioBus extends Action
{
	var $tpl = "tpl/envio/tpl.EnvioBus.php";

	function inicializar(){
		
	}

	function validar(&$v){

	}

	function buscar(){
		$listado = Listados::create('ListadoEnvios', $_GET);
		$this->asignar("listado", $listado->imprimir_listado());
	}
	
	function limpiar() {
		
	}
}
?>