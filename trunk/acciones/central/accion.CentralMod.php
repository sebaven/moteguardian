<?
include_once BASE_DIR ."clases/negocio/clase.Central.php";
include_once BASE_DIR ."clases/listados/clase.Listados.php";

/**
 * @author aamantea
 */
class CentralMod extends Action {
	var $tpl = "tpl/central/tpl.CentralAlta.php";

	function inicializar() {
		
		// Se incializa el combo de tecnologías de recoleccion
		$this->asignar('options_tecnologias', ComboTecnologiaRecoleccion(false));
		// Se inicializa el combo de tecnologías de central
		$this->asignar('options_tecnologias_central', ComboTecnologiaCentral(false));
		
		//Se cargan los datos de la central
		$central = new Central(intval($_GET['id']));
		$this->asignarArray($central->toArray());
		
		$daoTecnologiaRecolector = new TecnologiaRecolectorDAO();
		$tecnologiaRecolector = $daoTecnologiaRecolector->getById($central->id_tecnologia_recolector);
		$this->asignar('accion_tecnologia_recolector',$tecnologiaRecolector->accion);
	
		$this->asignar('idCentral',intval($_GET['id']));
	}


	function validar(&$v) {
		$v->add(new Required('nombre', 'central.nombre.required'), true);
		$v->add(new Required('procesador', 'central.procesador.required'), true);
		$v->add(new Required('id_tecnologia_recolector', 'central.tecnologiaRecoleccion.required'), true);
		$v->add(new Required('id_tecnologia_central', 'central.tecnologiaCentral.required'), true);
		$v->add(new Condition($this->validarNombreUnico(trim($_POST['nombre']),$_GET['id']),'central.nombre_existente'),true);
		$v->add(new Condition($this->validarProcesadorUnico(trim($_POST['procesador']),$_GET['id']),'central.procesador_existente'),true);
		$v->add(new Condition(strpos($_POST['nombre'].$_POST['descripcion'].$_POST['procesador'],'"') === false,'central.comillas.prohibidas'),true);
	}


	/**
	 * Esta funcion valida que el nombre de la central sea único y 
	 * que no exista ninguna central con ese nombre
	 * Devuelve true si el nombre es unico o false si existia alguna central con ese nombre
	 *
	 */
	function validarNombreUnico($nombre, $idCentral) {
		$dao = new CentralDAO();
		$central = $dao->getByNombre($nombre);
		$valido = false;
		if (isset($central) && ($central->id == $idCentral)) $valido = true;
		if (!isset($central)) $valido = true;
		return ($valido);
	}
	
	/**
	 * Esta funcion valida que el procesador de la central sea único y 
	 * que no exista ninguna central con ese procesador
	 * Devuelve true si el procesador es unico o false si existia alguna central con ese procesador
	 *
	 */
	function validarProcesadorUnico($procesador,$idCentral) {
		$dao = new CentralDAO();
		$central = $dao->getByProcesador($procesador);
		$valido = false;
		if (isset($central) && ($central->id == $idCentral)) $valido = true;
		if (!isset($central)) $valido = true;
		return ($valido);
	}
	function configurar() {
		$this->asignar("valido", "1");
	}
	
	
	function setearAccion() {
		$daoTecnologiaRecolector = new TecnologiaRecolectorDAO();
		$tecnologiaRecolector = $daoTecnologiaRecolector->getById($_POST['id_tecnologia_recolector']);
		$this->asignar('accion_tecnologia_recolector',$tecnologiaRecolector->accion);	
	}
}
?>