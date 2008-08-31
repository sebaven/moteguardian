<?php
// Listados
include_once BASE_DIR."clases/listados/clase.Listados.php";

// Clases de negocio
include_once BASE_DIR."clases/negocio/clase.Recoleccion.php";
include_once BASE_DIR."clases/negocio/clase.InformacionEstado.php";

// DAOS
include_once BASE_DIR."clases/dao/dao.Recoleccion.php";
include_once BASE_DIR."clases/dao/dao.Planificacion.php";
include_once BASE_DIR.'clases/dao/dao.InformacionEstado.php';


class RecoleccionConf extends Action {
	
	
	var $tpl = "tpl/recoleccion/tpl.RecoleccionConf.php";

	
	function inicializar() {
		// Cargo los combos		
		$this->asignar("tecnologias_central", ComboTecnologiaCentral(true,""));
		$this->asignar("tecnologias_recoleccion", ComboTecnologiaRecoleccion(true,""));
				
		if($_POST['id_recoleccion']) $this->guardarDatos();
		$this->actualizarPantalla();
	}

	
	function validar(&$v){
		$v->add(new Required('nombre_recoleccion', 'recoleccion.conf.nombre.requerido'), false);
		$v->add(new Condition($this->validarCentralesRequired($_POST['id_recoleccion']), 'recoleccion.conf.centrales.requerido'),false);		
		
		$recoleccionDAO = new RecoleccionDAO();
		if( count($recoleccionDAO->getNombreRecoleccionMenosLaPasadaPorParametro($_POST['id_recoleccion'], trim($_POST['nombre_recoleccion']))) != 0 ){
			$v->add(new Condition(false,'recoleccion.conf.nombre.duplicado'), false);
		}
		
		if($_POST['habilitado_recoleccion']) {
			$planificacionDAO = new PlanificacionDAO();
			$planificaciones = $planificacionDAO->getPlanificacionesDeRecoleccion($_POST['id_recoleccion']);
			if(count($planificaciones)<=0) {
				$v->add(new Condition(false, 'recoleccion.conf.habilitado.sin.planificaciones'), false);
			}
		}
	}

	
	function validarCentralesRequired($idRecoleccion){
		$centralDAO = new CentralDAO();
		$centralesAsociadas = $centralDAO->getCentralesByRecoleccion($idRecoleccion);
		if(count($centralesAsociadas)<=0) return false;
		else return true;
	}
	
	
	function guardarDatos(){		
		// Obtengo una referencia a la recolección que estoy manejando
		if($_GET['id_recoleccion']) $recoleccion = new Recoleccion($_GET['id_recoleccion']);			
		else if ($_POST['id_recoleccion']) $recoleccion = new Recoleccion($_POST['id_recoleccion']);			
		else return;
				
		// Cargo los datos de la pantalla
		$recoleccion->nombre = trim($_POST['nombre_recoleccion']);
		if($_POST['habilitado_recoleccion']) $recoleccion->habilitado = TRUE_;
		else $recoleccion->habilitado = FALSE_;
		
		// Los guardo en la base de datos
		$recoleccion->manual = FALSE_;
		$recoleccion->save();
		
		// Y se crean las informaciones de estado correspondientes
		$infoEstadoDAO = new InformacionEstadoDAO();
		$ie = $infoEstadoDAO->filterByField("id_recoleccion",$recoleccion->id);
		if (count($ie) == 0) {
			$informacionEstado = new InformacionEstado();
		} else $informacionEstado = $ie[0];
		$informacionEstado->id_recoleccion = $recoleccion->id;
		$informacionEstado->nombre_estado = "Recolectado por recolección \"".$recoleccion->nombre."\"";
		$informacionEstado->save();
				
		// cuando se llama desde procesar ponerle después temporal = FALSE_
		return $recoleccion;
	}
	
	
	function limpiar(){		
	}
	
	
	function recargar(){			
	}
	
	
	function buscar(){		
		$this->agregarCentrales();
		
		// Cargo los parámetros de búsqueda pueden venir por post o por get
		if($_POST['tecnologia_central_busqueda']) $parametros['id_tecnologia_central'] = $_POST['tecnologia_central_busqueda'];
		else $parametros['id_tecnologia_central'] = $_GET['tecnologia_central_busqueda'];
		
		if($_POST['tecnologia_recoleccion_busqueda']) $parametros['id_tecnologia_recolector'] = $_POST['tecnologia_recoleccion_busqueda'];
		else $parametros['id_tecnologia_recolector'] = $_GET['id_tecnologia_recolector'];
		
		if($_POST['nombre_central_busqueda']) $parametros['nombre'] = $_POST['nombre_central_busqueda'];
		else $parametros['nombre'] = $_GET['nombre'];
		
		if($POST['procesador_central_busqueda']) $parametros['procesador'] = $_POST['procesador_central_busqueda'];
		else $parametros['procesador'] = $_GET['procesador'];
		 
		$parametros['para_seleccion'] = TRUE_;
		
		if($_GET['id_recoleccion']) $parametros['id_recoleccion_que_esta_siendo_configurada'] = $_GET['id_recoleccion'];
		else $parametros['id_recoleccion_que_esta_siendo_configurada'] = $_POST['id_recoleccion'];
		
		// cargo el listado
		$listadoCentralesBusqueda = Listados::create('ListadoCentralesParaSeleccionar', $parametros);
		$this->asignar("listado_centrales_resultado_busqueda", $listadoCentralesBusqueda->imprimir_listado());
			
		$this->actualizarPantalla();		
	}
	
	
	function agregarCentrales() {				
		$centralDAO = new CentralDAO();
		$idsCentrales = $centralDAO->getIdsCentralesNoAsignadas();
				
		for($i=0; $i<count($idsCentrales); $i++){
			if($_POST['id_fila_'.$idsCentrales[$i]->id]) {
				$central = new Central($idsCentrales[$i]->id);				
				$central->id_recoleccion = $_POST['id_recoleccion'];			
				$central->save();			
			}
		}
					
		$this->actualizarPantalla();							
	}
	
	
	function procesar(&$nextAction){
		$recoleccion = $this->guardarDatos();
		$recoleccion->temporal = FALSE_;
		$recoleccion->manual = FALSE_;
		
		if($recoleccion->save()){
			$nextAction->setNextAction('Inicio', 'recoleccion.conf.guardado.ok');
		} else {
			$nextAction->setNextAction('Inicio', 'recoleccion.conf.guardado.error',array(error => "1"));
		}
	}
	
	
	function actualizarPantalla(){
		// Creo el objeto recolección sobre el cual se va a trabajar				
		if ($_GET['id_recoleccion']) {
			// Llega por GET, por lo tanto estamos en una modificación			
			$recoleccion = new Recoleccion($_GET['id_recoleccion']);						
		} else if ($_POST['id_recoleccion']) {
			// Llega por POST, por lo tanto estamos en un reposteo			
			$recoleccion = new Recoleccion($_POST['id_recoleccion']);
		} else {
			// No llega por ningún lado, es un alta
			$recoleccion = new Recoleccion();
			$recoleccion->baja_logica = FALSE_;
			$recoleccion->temporal = TRUE_;
			$recoleccion->habilitado = FALSE_;
			$recoleccion->manual = FALSE_;
			$recoleccion->save();			
		}
		
		// Cargo los datos de la recoleccion		
		$this->asignar('id_recoleccion',$recoleccion->id);
		$this->asignar('nombre_recoleccion',$recoleccion->nombre);
		if($recoleccion->habilitado==TRUE_) $this->asignar('habilitado_recoleccion_checked', "checked" );
				
		// Cargo los listados		
		// Planificaciones
		$vector['id'] = $recoleccion->id;		
		$listado = Listados::create('ListadoPlanificacion', $vector);		
		$this->asignar("listado_planificaciones", $listado->imprimir_listado());
		
		// Centrales asignadas				
		$vectorCentrales['id_recoleccion'] = $recoleccion->id;				
		$listadoCentrales = Listados::create('ListadoCentralesAsignadasARecoleccion', $vectorCentrales);
		$this->asignar("listado_centrales", $listadoCentrales->imprimir_listado());
		
		// Cargo los parámetros de la búsqueda de la central (Para paginados)
		$this->asignar('id_tecnologia_central_seleccionada', $_POST['tecnologia_central_busqueda']);
		$this->asignar('id_tecnologia_recoleccion_seleccionada', $_POST['tecnologia_recoleccion_busqueda']);
		$this->asignar('nombre_central_busqueda', $_POST['nombre_central_busqueda']);
		$this->asignar('procesador_central_busqueda', $_POST['procesador_central_busqueda']);
	}
}

?>