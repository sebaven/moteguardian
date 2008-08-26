<?
include_once BASE_DIR ."clases/listados/clase.Listados.php";
/**
 * @author aamantea
 */
class CentralAdm extends Action
{
	var $tpl = "tpl/central/tpl.CentralAdm.php";	
	
	function inicializar() {
		// Se incializa el combo de tecnologías de recoleccion
		$this->asignar('options_tecnologias', ComboTecnologiaRecoleccion(true));
		
	}
	
	function validar(&$v) {
		if(isset($_GET['btnConfigurar'])) {
			$v->add(new Required('nombre', 'central.nombre.required'), true);
			$v->add(new Required('codigo', 'central.codigo.required'), true);
			$v->add(new Required('id_tecnologia_recolector', 'central.tecnologia.required'), true);
		}
	}
	
	
	
	function buscar()
	{
		// Cargar Datos de la Busqueda
		$this->asignarArray($_GET);
	
		$listado = Listados::create('ListadoCentrales', $_GET);
		$this->asignar("listado", $listado->imprimir_listado());
	}
}
?>