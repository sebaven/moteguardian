<?php
// Listados
include_once BASE_DIR."clases/listados/clase.Listados.php";

// Clases de negocio
include_once BASE_DIR."clases/negocio/clase.Recoleccion.php";
include_once BASE_DIR."clases/negocio/clase.InformacionEstado.php";
include_once BASE_DIR."clases/negocio/clase.CentralRecoleccionManual.php";

// DAOS
include_once BASE_DIR."clases/dao/dao.Recoleccion.php";
include_once BASE_DIR."clases/dao/dao.Planificacion.php";
include_once BASE_DIR."clases/dao/dao.Configuracion.php";
include_once BASE_DIR.'clases/dao/dao.InformacionEstado.php';


class RecoleccionManualConf extends Action {
	
	var $tpl = "tpl/recoleccion/tpl.RecoleccionManualConf.php";
	
	function inicializar() {
		// Cargo los combos		
		$this->asignar("tecnologias_central", ComboTecnologiaCentral(true,""));
		$this->asignar("tecnologias_recoleccion", ComboTecnologiaRecoleccion(true,""));
				
		if($_GET['id_tecnologia_central']) $this->asignar("id_tecnologia_central", $_GET['id_tecnologia_central']);
		if($_GET['id_tecnologia_recoleccion']) $this->asignar("id_tecnologia_recoleccion", $_GET['id_tecnologia_recoleccion']);
		
		$this->guardarDatos();
		$this->actualizarPantalla();
	}

	
	function validar(&$v){
		$v->add(new Required('nombre_recoleccion_manual', 'recoleccion.conf.nombre.requerido'), false);
		
		$centralDAO = new CentralDAO();
		if(count($centralDAO->getIdsAsignadasRecoleccionManual($_GET['id_recoleccion_manual'])) == 0 ) {
			$v->add(new Condition(false,'recoleccionManual.conf.seleccionar.centrales'), false);
		}
		
		$recoleccionDAO = new RecoleccionDAO();
		if(count($recoleccionDAO->getNombreRecoleccionMenosLaPasadaPorParametro($_GET['id_recoleccion_manual'], trim($_GET['nombre_recoleccion_manual']))) != 0 ) {
			$v->add(new Condition(false,'recoleccion.conf.nombre.duplicado'), false);
		}
		
		if($_GET['fecha_desde']&&$_GET['fecha_hasta']) {			
			$v->add(new Condition($_GET['fecha_hasta'] >= $_GET['fecha_desde'] , "monitoreoTareas.rango.inconsistente"), true);			
		}
		
		$configuracionDAO = new ConfiguracionDAO();
		$config = $configuracionDAO->getById(CONST_CONFIG_ESTADO_SRDFIP);
		if ($config->valor != ESTADO_SRDFIP_EJECUTANDO) {
			$v->add(new Condition(false,'recoleccionManual.conf.aplicacion.detenida'), false);
		}				
	}

	
	function guardarDatos(){		
		// Obtengo una referencia a la recolección que estoy manejando
		if($_GET['id_recoleccion_manual']) $recoleccion = new Recoleccion($_GET['id_recoleccion_manual']);			
		else if ($_GET['id_recoleccion_manual']) $recoleccion = new Recoleccion($_GET['id_recoleccion_manual']);			
		else {
			$recoleccion = new Recoleccion();
			$recoleccion->baja_logica = FALSE_;
			$recoleccion->temporal = TRUE_;
			$recoleccion->habilitado = FALSE_;
			$recoleccion->manual = TRUE_;
			$recoleccion->save();
		}			
				
		// Cargo los datos de la pantalla
		$recoleccion->nombre = trim($_GET['nombre_recoleccion_manual']);				
		
		// Los guardo en la base de datos		
		$recoleccion->manual = TRUE_;
		$recoleccion->save();
		
		// Y se crean las informaciones de estado correspondientes
		$infoEstadoDAO = new InformacionEstadoDAO();
		$ie = $infoEstadoDAO->filterByField("id_recoleccion",$recoleccion->id);
		if (count($ie) == 0) {
			$informacionEstado = new InformacionEstado();
		} else $informacionEstado = $ie[0];
		$informacionEstado->id_recoleccion = $recoleccion->id;
		$informacionEstado->nombre_estado = "Recolectado por recolección manual \"".$recoleccion->nombre."\"";
		$informacionEstado->save();
		
		// cuando se llama desde procesar ponerle después temporal = FALSE_
		return $recoleccion;
	}

	
	function recargar(){
	}
	
	
	function buscar(){			
		// cargo el listado
		$listadoCentralesBusqueda = Listados::create('ListadoCentralesParaSeleccionar', $_GET);
		$this->asignar("listado_centrales_resultado_busqueda", $listadoCentralesBusqueda->imprimir_listado());		
		$this->actualizarPantalla();
	}
	
	
	function agregarCentrales() {
		$centralDAO = new CentralDAO();
		$idsCentrales = $centralDAO->getIdsNoAsignadasRecoleccionManual($_GET['id_recoleccion_manual']);
		for($i=0; $i<count($idsCentrales['id']); $i++){
			if($_GET['id_fila_'.$idsCentrales['id'][$i]]) {
				$central_rec_manual = new CentralRecoleccionManual();
				$central_rec_manual->id_central = $idsCentrales['id'][$i];
				$central_rec_manual->id_recoleccion = $_GET['id_recoleccion_manual'];				
				$central_rec_manual->save();
			}
		}
		$this->actualizarPantalla();						
	}
	
	
	function procesar(&$nextAction){
		$recoleccion = $this->guardarDatos();
		$recoleccion->temporal = FALSE_;	
		$recoleccion->habilitada = TRUE_;	
		
		if($recoleccion->save()){
			//Se crea una fecha y se obtiene la actual
			$fecha = new Fecha();
			$fecha->loadFromNow();
			//Se le suma x segundos a la hora para q la planificacion se realice en el prox. ciclo del dispacher.
			$fecha->addSeconds(30); //FIXME: sacar el dato de la configuracion del dispatcher
							
			//Creo una planificacion y agrego sus datos correspondientes.
			$planificacion = new Planificacion();
			$planificacion->id_recoleccion = $recoleccion->id; 
			$planificacion->hora = $fecha->hora.":".$fecha->minutos.":".$fecha->segundos;			
			$planificacion->dia_absoluto = $fecha->anio."-".$fecha->mes."-".$fecha->dia; 			
			$planificacion->dia_semana = "NULL";	
			$planificacion->id_envio = "NULL";
			$planificacion->baja_logica = FALSE_;
			$fechaAyer = new Fecha();
			$fechaAyer->loadFromYesterday();
			$planificacion->fecha_vigencia = $fechaAyer->anio."-".$fechaAyer->mes."-".$fechaAyer->dia;
			
			$res = $planificacion->save();
			
			if($res) {
				$nextAction->setNextAction('Inicio', 'recoleccionManual.conf.lanzada.ok');	
			} else {
				$nextAction->setNextAction('Inicio', 'recoleccionManual.conf.guardado.error',array(error => "1"));
			}
		} else {
			$nextAction->setNextAction('Inicio', 'recoleccionManual.conf.guardado.error',array(error => "1"));
		}
	}
	
	
	function actualizarPantalla(){
		// Creo el objeto recolección sobre el cual se va a trabajar				
		if($_GET['id_recoleccion_manual']) {					
			$recoleccion = new Recoleccion($_GET['id_recoleccion_manual']);
		} else if($_GET['id_recoleccion_manual']) {
			$recoleccion = new Recoleccion($_GET['id_recoleccion_manual']); 
		} else {
			// No llega por ningún lado, es un alta
			$recoleccion = new Recoleccion();
			$recoleccion->baja_logica = FALSE_;
			$recoleccion->temporal = TRUE_;
			$recoleccion->habilitado = FALSE_;
			$recoleccion->manual = TRUE_;
			$recoleccion->save();			
		}
		
		// Cargo los datos de la recoleccion		  
		$this->asignar('id_recoleccion_manual',$recoleccion->id);
		if($_GET['nombre_recoleccion_manual']) $this->asignar('nombre_recoleccion_manual',$_GET['nombre_recoleccion_manual']);
		else $this->asignar('nombre_recoleccion_manual',$recoleccion->nombre);
		// Se carga el listado de centrales asignadas				
		$vectorCentrales['id_recoleccion_manual'] = $recoleccion->id;				
		$listadoCentrales = Listados::create('ListadoCentralesAsignadasARecoleccionManual', $vectorCentrales);
		$this->asignar("listado_centrales", $listadoCentrales->imprimir_listado());
	}
}

?>