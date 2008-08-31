<?
include_once BASE_DIR ."clases/dao/dao.Host.php";

/**
 * @author aamantea
 */
class HostAlta extends Action
{
	var $tpl = "tpl/enviador/tpl.HostAlta.php";	
	
	function inicializar() {
		
		// Se incializa el combo de tecnologías de envio
		$this->asignar('options_tecnologias', ComboTecnologiaEnviador(true));
	
	}
	
	function validar(&$v) {
		if(isset($_POST['btnConfigurar'])) {
			$v->add(new Required('nombre', 'host.nombre.required'), true);
			$v->add(new Required('id_tecnologia_envio', 'host.tecnologia.required'), true);
			$v->add(new Condition($this->validarNombreUnico($_POST['nombre']),'host.nombre_existente'),true);
			$v->add(new Condition(strpos($_POST['nombre'].$_POST['descripcion'],'"') === false,'central.comillas.prohibidas'),true);
		}
	}

	/**
	 * Esta funcion valida que el nombre de la host sea único y 
	 * que no exista ningun host con ese nombre
	 * Devuelve true si el nombre es unico o false si existia algun host con ese nombre
	 *
	 */
	function validarNombreUnico($nombre) {
		$dao = new HostDAO();
		$host = $dao->getByNombre($nombre);
		return (!isset($host));
	}
	
	function configurar()
	{
		$this->asignar("valido", "1");
	}
	
	function setearAccion() {
		$daoTecnologiaEnviador = new TecnologiaEnviadorDAO();
		$tecnologiaEnviador = $daoTecnologiaEnviador->getById($_POST['id_tecnologia_envio']);
		$this->asignar('accion_tecnologia_envio',$tecnologiaEnviador->accion);
	}
}
?>