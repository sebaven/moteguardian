<?
include_once BASE_DIR .'validadores/NumericInteger.php';
include_once BASE_DIR ."clases/dao/dao.TecnologiaCentral.php";
include_once BASE_DIR ."clases/negocio/clase.TecnologiaCentral.php";
include_once BASE_DIR ."clases/listados/clase.Listados.php";

/**
 * @author cgalli
 */
class TecnologiaCentralAdmin extends Action
{
	var $tpl = "tpl/central/tpl.TecnologiaCentralAdmin.php";
	
	function inicializar() {		
		// Verifico si se está haciendo una modificación de una tecnología existente
		
		if($_GET['id_tecnologia_central']){						
			$tecnologiaCentral = new TecnologiaCentral($_GET['id_tecnologia_central']);
			$this->asignar('accion_tecnologia_central','crearTecnologia');
			$this->asignar('nombre_nueva_tecnologia', $tecnologiaCentral->nombre );
			$this->asignar('version_nueva_tecnologia', $tecnologiaCentral->version );
			$this->asignar('descripcion_nueva_tecnologia', $tecnologiaCentral->descripcion);
			$this->asignar('id_tecnologia_central', $tecnologiaCentral->id);					
		} else if ($_POST['nombre_nueva_version']) {
			$tecnologiaCentral = new TecnologiaCentral($_POST['id_tecnologia_central']);
			$this->asignar('accion_tecnologia_central','crearTecnologia');
			$this->asignar('nombre_nueva_tecnologia', trim($_POST['nombre_nueva_version']));
			$this->asignar('version_nueva_tecnologia', trim($_POST['version_nueva_tecnologia']));
			$this->asignar('descripcion_nueva_tecnologia', trim($_POST['descripcion_nueva_descripcion']));			
		} else {
			$this->asignar('accion_tecnologia_central','ninguna');			
		}
		
		$this->asignar('combo_nombre_tecnologia_central', ComboNombreTecnologiaCentral(false));
		
		$listado = Listados::create('ListadoTecnologiaCentral');
		$this->asignar('listado_tecnologias_central', $listado->imprimir_listado());		
	}
	
	
	
	function validar(&$v) {		
		// Agrego las validaciones según que operación se esté realizando
				
		$accionTecnologiaCentral = $_POST['accion_tecnologia_central']; 
		if($accionTecnologiaCentral == 'ninguna') {
			
			// No se está haciendo ninguna operación, no agrego validación
			
		} else if($accionTecnologiaCentral == 'agregarVersion') {

			$v->add(new Required('nombre_nueva_version','tecnologia.central.nombre.obligatorio'),true);
			$v->add(new Required('version_nueva_version','tecnologia.central.version.obligatorio'),true);
			$v->add(new Required('descripcion_nueva_version','tecnologia.central.descripcion.obligatorio'),true);
			$v->add(new Condition($this->validarVersionUnicaEnNombre(trim($_POST['nombre_nueva_version']), trim($_POST['version_nueva_version']), 0),'tecnologia.central.version.duplicada'), true);						
			$v->add(new Numeric('version_nueva_version', 'tecnologia.central.version.numerica'), true);
			
		} else if($accionTecnologiaCentral == 'crearTecnologia') {		
			
			$v->add(new Required('nombre_nueva_tecnologia','tecnologia.central.nombre.obligatorio'),true);
			$v->add(new Required('version_nueva_tecnologia','tecnologia.central.version.obligatorio'),true);			
			$v->add(new Required('descripcion_nueva_tecnologia','tecnologia.central.descripcion.obligatorio'),true);			
			$v->add(new Condition($this->validarVersionUnicaEnNombre(trim($_POST['nombre_nueva_tecnologia']), trim($_POST['version_nueva_tecnologia']), $_GET['id_tecnologia_central']),'tecnologia.central.version.duplicada'), true);
			$v->add(new Numeric('version_nueva_tecnologia', 'tecnologia.central.version.numerica'), true);			
			
		}			
				
	}
	
	
	
	
	function validarVersionUnicaEnNombre($nombre, $version, $id) {
		$tecnologiaCentralDAO = new TecnologiaCentralDAO();
		if(count($tecnologiaCentralDAO->findTecnologiaByNombreYVersion($nombre, $version, $id)) != 0)	return false;
		else return true;
	}

	
		
	function procesar(&$nextAction){
		// Creo una nueva tecnologia central u obtengo la vieja de la base de datos
		if($_GET['id_tecnologia_central']) $tecnologiaCentral = new TecnologiaCentral($_GET['id_tecnologia_central']);
		else $tecnologiaCentral = new TecnologiaCentral();
		
		// Le seteo los campos
		
		$tecnologiaCentral->baja_logica = FALSE_;

		$accionTecnologiaCentral = $_POST['accion_tecnologia_central']; 
		if($accionTecnologiaCentral=='ninguna') {				
			
		} else if ($accionTecnologiaCentral == 'crearTecnologia'){
			
			$tecnologiaCentral->nombre = trim($_POST['nombre_nueva_tecnologia']);
			$tecnologiaCentral->version = trim($_POST['version_nueva_tecnologia']);
			$tecnologiaCentral->descripcion = trim($_POST['descripcion_nueva_tecnologia']);
			
		} else if($accionTecnologiaCentral=='agregarVersion') {
			
			$tecnologiaCentral->nombre = trim($_POST['nombre_nueva_version']);
			$tecnologiaCentral->version = trim($_POST['version_nueva_version']);
			$tecnologiaCentral->descripcion = trim($_POST['descripcion_nueva_version']);
			
		}
		
		// La guardo y redirijo a la página de administración de centrales
		
		if($tecnologiaCentral->save()) {
			$nextAction->setNextAction('TecnologiaCentralAdmin', 'tecnologia.central.guardado.exitoso');
		} else {
			$nextAction->setNextAction('TecnologiaCentralAdmin', 'tecnologia.central.guardado.erroneo',array(error => "1"));
		}
	}
}
?>