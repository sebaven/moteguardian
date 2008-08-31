<?
// CLASES DE NEGOCIO
include_once BASE_DIR . 'clases/negocio/clase.Actividad.php';
include_once BASE_DIR . 'clases/negocio/clase.Recoleccion.php';

// DAOS
include_once BASE_DIR . 'clases/dao/dao.Actividad.php';

// LISTADOS
include_once BASE_DIR . 'clases/listados/clase.Listados.php';



class ActividadAdm extends Action
{
	var $tpl = "tpl/actividad/tpl.ActividadAdm.php";	

	function inicializar() {
		if($_POST['id_actividad']) $this->guardarDatos();
		$this->actualizarPagina();		
		
		$this->asignar('plantillas', ComboPlantillaRecoleccion());
	}
	
	function validar(&$v) {
		$v->add(new Required('nombre_actividad','actividad.conf.nombre.required'), false);

		$actividadDAO = new ActividadDAO();
		if( count($actividadDAO->getNombreActividadMenosLaPasadaPorParametro($_POST['id_actividad'], trim($_POST['nombre_actividad']))) != 0 ){
			$v->add(new Condition(false,'actividad.nombre.duplicado'), false);
		}
		
		$v->add(new Condition($this->validarRecoleccionRequerida($_POST['id_actividad']) ,'actividad.recoleccion.required'),false);
	}
		
	function procesar(&$nextAction){
		$actividad = $this->guardarDatos();
		$actividad->temporal = FALSE_; 	
			
		if($actividad->save())
			$nextAction->setNextAction("ActividadBuscar", "actividad.guardado.ok");
		else
			$nextAction->setNextAction("ActividadBuscar", "actividad.guardado.error",array(error => "1"));
	}
	
	function validarRecoleccionRequerida($idActividad) {
		if($_POST['id_actividad']) $this->guardarDatos();
		$recoleccionDAO = new RecoleccionDAO();
		$recoleccionesAsignadas = $recoleccionDAO->getRecoleccionesAgregadas($idActividad);
		if(count($recoleccionesAsignadas)<=0) return false;
		else return true; 
	}
	
	function guardarDatos(){
		$actividad = new Actividad($_POST['id_actividad']);
		$actividad->baja_logica = FALSE_;		
		$actividad->nombre = trim($_POST['nombre_actividad']);
		$actividad->id_plantilla = $_POST['plantilla'];		
		
		if($_POST["recolecciones_agregadas"]){			
			foreach($_POST["recolecciones_agregadas"] as $recoleccionAgregada){
				$recoleccion = new Recoleccion($recoleccionAgregada);			
				$recoleccion->id_actividad = $_POST['id_actividad'];								
				$recoleccion->save();				
			}
		} 
		if($_POST["recolecciones"]) {
			foreach($_POST["recolecciones"] as $recoleccionNoAgregada){
				$recoleccion = new Recoleccion($recoleccionNoAgregada);			
				$recoleccion->id_actividad = 'NULL';								
				$recoleccion->save();				
			}
		}
		
		$actividad->save();

		return $actividad;
	}
	
	function actualizarPagina() {		
		// Se obtiene el objeto que se va a configurar		
		if($_GET['id_actividad']) $actividad = new Actividad($_GET['id_actividad']);
		else if($_POST['id_actividadd']) $actividad = new Actividad($_POST['id_actividad']);
		else{
			$actividad = new Actividad();
			$actividad->baja_logica = FALSE_;			
			$actividad->temporal = TRUE_;
			$actividad->save();						
		}
		
		// Se asignan las variables de la pantalla		
		$this->asignar('nombre_actividad', $actividad->nombre);		
		$this->asignar('id_actividad', $actividad->id);
		$this->asignar('id_plantilla', $actividad->id_plantilla);
		
		// Se cargan las recolecciones 
		$recolecciones = ComboRecoleccionesSinAsignar();
		$recoleccionesAgregadas = ComboRecoleccionesAgregadas($actividad->id);
		
		$this->asignar('ids_recolecciones', $recolecciones);
		$this->asignar('ids_recolecciones_agregadas',$recoleccionesAgregadas);
	}
}
?>
