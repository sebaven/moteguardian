<?
include_once BASE_DIR ."clases/listados/clase.Listados.php";
/**
 * @author aamantea
 */
class HostAdm extends Action
{
	var $tpl = "tpl/enviador/tpl.HostAdm.php";	
	
	function inicializar() {
		// Se incializa el combo de tecnologías de recoleccion
		$this->asignar('options_tecnologias', ComboTecnologiaEnviador(true));
		
	}
	
	function validar(&$v) {
		if(isset($_GET['btnConfigurar'])) {
			$v->add(new Required('nombre', 'host.nombre.required'), true);
			$v->add(new Required('procesador', 'host.procesador.required'), true);
			$v->add(new Required('id_tecnologia_envio', 'host.tecnologia.required'), true);
		}
	}
	
	
	
	function buscar()
	{
		// Cargar Datos de la Busqueda
		$this->asignarArray($_GET);
	
		$listado = Listados::create('ListadoHosts', $_GET);
		$this->asignar("listado", $listado->imprimir_listado());
	}
}
?>