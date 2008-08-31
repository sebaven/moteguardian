<?
include_once BASE_DIR ."clases/negocio/clase.Host.php";
include_once BASE_DIR ."clases/listados/clase.Listados.php";
include_once BASE_DIR ."clases/dao/dao.Host.php";
include_once BASE_DIR ."clases/dao/dao.TecnologiaEnviador.php";

/**
 * @author aamantea
 */
class HostMod extends Action {
	var $tpl = "tpl/enviador/tpl.HostAlta.php";

	function inicializar() {
		
		// Se incializa el combo de tecnologías de envio
		$this->asignar('options_tecnologias', ComboTecnologiaEnviador(true));
		
		//Se cargan los datos del host
		$host = new Host(intval($_GET['id']));
		$this->asignarArray($host->toArray());
		
		$this->asignar('id_tecnologia_envio',$host->id_tecnologia_enviador);
		
		$daoTecnologiaEnviador = new TecnologiaEnviadorDAO();
		$tecnologiaEnviador = $daoTecnologiaEnviador->getById($host->id_tecnologia_enviador);
		$this->asignar('accion_tecnologia_envio',$tecnologiaEnviador->accion);
	
		$this->asignar('idHost',$_GET['id']);
	}


	function validar(&$v) {
		$v->add(new Required('nombre', 'host.nombre.required'), true);
		$v->add(new Required('id_tecnologia_envio', 'host.tecnologia.required'), true);
		$v->add(new Condition($this->validarNombreUnico(trim($_POST['nombre']),$_GET['id']),'host.nombre_existente'),true);
		$v->add(new Condition(strpos($_POST['nombre'].$_POST['descripcion'],'"') === false,'host.comillas.prohibidas'),true);
	}


	/**
	 * Esta funcion valida que el nombre del host sea único y 
	 * que no exista ningun host con ese nombre
	 * Devuelve true si el nombre es unico o false si existia algun host con ese nombre
	 *
	 */
	function validarNombreUnico($nombre, $idHost) {
		$dao = new HostDAO();
		$host = $dao->getByNombre($nombre);
		$valido = false;
		if (isset($host) && ($host->id == $idHost)) $valido = true;
		if (!isset($host)) $valido = true;
		return ($valido);
	}
	

	function configurar() {
		$this->asignar("valido", "1");
	}
	
	function setearAccion() {
		$daoTecnologiaEnvio = new TecnologiaEnvioDAO();
		$tecnologiaEnvio = $daoTecnologiaEnvio->getById($_POST['id_tecnologia_envio']);
		$this->asignar('accion_tecnologia_envio',$tecnologiaEnvio->accion);
	}
}
?>