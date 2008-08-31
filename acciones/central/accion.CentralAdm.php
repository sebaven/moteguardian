<?
include_once BASE_DIR ."clases/listados/clase.Listados.php";
/**
 * @author aamantea
 */
class CentralAdm extends Action {
	
	var $tpl = "tpl/central/tpl.CentralAdm.php";	
	
	function inicializar() {
		// Se incializa el combo de tecnologías de recoleccion
		$this->asignar('options_tecnologias', ComboTecnologiaRecoleccion(true));
	}
			
	function buscar() {	
		$listado = Listados::create('ListadoCentrales', $_GET);
		$this->asignar("listado", $listado->imprimir_listado());
	}
}
?>