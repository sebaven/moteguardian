<?
/**
 * @author aamantea
 */
class CentralAlta extends Action
{
	var $tpl = "tpl/central/tpl.CentralAlta.php";	
	
	function inicializar() {
		
		// Se incializa el combo de tecnolog�as de recoleccion
		$this->asignar('options_tecnologias', ComboTecnologiaRecoleccion(true));
		$this->asignar('options_tecnologias_central', ComboTecnologiaCentral(false));
	
	}
	
	function validar(&$v) {
		if(isset($_POST['btnConfigurar'])) {
			$v->add(new Required('nombre', 'central.nombre.required'), true);
			$v->add(new Required('codigo', 'central.codigo.required'), true);
			$v->add(new Required('id_tecnologia_recolector', 'central.tecnologia.required'), true);
			$v->add(new Condition($this->validarNombreUnico($_POST['nombre']),'central.nombre_existente'),true);
			$v->add(new Condition($this->validarCodigoUnico($_POST['codigo']),'central.codigo_existente'),true);
			$v->add(new Condition(strpos($_POST['nombre'].$_POST['descripcion'].$_POST['codigo'],'"') === false,'central.comillas.prohibidas'),true);
		}
	}

	/**
	 * Esta funcion valida que el nombre de la central sea �nico y 
	 * que no exista ninguna central con ese nombre
	 * Devuelve true si el nombre es unico o false si existia alguna central con ese nombre
	 *
	 */
	function validarNombreUnico($nombre) {
		$dao = new CentralDAO();
		$central = $dao->getByNombre($nombre);
		return (!isset($central));
	}
	
	/**
	 * Esta funcion valida que el codigo de la central sea �nico y 
	 * que no exista ninguna central con ese codigo
	 * Devuelve true si el codigo es unico o false si existia alguna central con ese codigo
	 *
	 */
	function validarCodigoUnico($codigo) {
		$dao = new CentralDAO();
		$central = $dao->getByCodigo($codigo);
		return (!isset($central));
	}
	
	function configurar()
	{
		$this->asignar("valido", "1");
	}
	
	function setearAccion() {
		$daoTecnologiaRecolector = new TecnologiaRecolectorDAO();
		$tecnologiaRecolector = $daoTecnologiaRecolector->getById($_POST['id_tecnologia_recolector']);
		$this->asignar('accion_tecnologia_recolector',$tecnologiaRecolector->accion);
		
	}
}
?>