<?php
// Listados
include_once BASE_DIR."clases/listados/clase.Listados.php";

// Clases de negocio
include_once BASE_DIR."clases/negocio/clase.Envio.php";
include_once BASE_DIR."clases/negocio/clase.InformacionEstado.php";
include_once BASE_DIR."clases/negocio/clase.FicheroEnvioManual.php";
include_once(BASE_DIR."clases/batch/entidades/clase.ProcesoEjecutandose.php");

// DAOS
include_once BASE_DIR."clases/dao/dao.Envio.php";
include_once BASE_DIR."clases/dao/dao.Planificacion.php";
include_once BASE_DIR."clases/dao/dao.Configuracion.php";
include_once BASE_DIR .'clases/dao/dao.InformacionEstado.php';

// Validadores
include_once BASE_DIR .'validadores/PositiveInteger.php';

class EnvioManualConf extends Action {

	var $tpl = "tpl/envio/tpl.EnvioManualConf.php";

	function inicializar() {
		// Cargo los combos
		$this->asignar("tecnologias_central", ComboTecnologiaCentral(true,""));
		$this->asignar("tecnologias_envio", ComboTecnologiaEnviador(true,""));

		if($_GET['tecnologia_central_busqueda']) $this->asignar("id_tecnologia_central_seleccionada", $_GET['tecnologia_central_busqueda']);
		if($_GET['tecnologia_envio_busqueda']) $this->asignar("id_tecnologia_envio_seleccionada", $_GET['tecnologia_envio_busqueda']);

		//$this->guardarDatos();
		$this->actualizarPantalla();
	}


	function validar(&$v){		
		$this->guardarDatos();		
				
		$v->add(new Required('nombre_envio_manual', 'envioManual.conf.nombre.requerido'), false);

		$tareaDAO = new TareaDAO();
		if(count($tareaDAO->getIdsAsignadasEnvioManual($_GET['id_envio_manual'])) == 0 ) {
			$v->add(new Condition(false,'envioManual.conf.seleccionar.ficheros'), false);
		}
						
		$hostDAO = new HostDAO();
		$hostsAgregados = $hostDAO->getHostsAsignadosAEnvio($_GET['id_envio_manual']);	
		if(count($hostsAgregados)<=0) {			
			$v->add(new Condition(false,'envio.host.obligatorio'),false);			
		}		
		
		$envioDAO = new EnvioDAO();
		if(count($envioDAO->getNombreEnvioMenosElPasadoPorParametro($_GET['id_envio_manual'], trim($_GET['nombre_envio_manual']))) != 0 ) {
			$v->add(new Condition(false,'envioManual.conf.nombre.duplicado'), false);
		}
		
		// Valido que se hayan ingresao números de tarea válidos para la búsqueda
		$v->add(new PositiveInteger('tarea_desde', "envioManual.conf.numero.tarea.positivo"), true);		
		$v->add(new PositiveInteger('tarea_hasta', "envioManual.conf.numero.tarea.positivo"), true);
				
		// Valido que el intervalo temporal de búsqueda ingresado sea coherente
		if($_GET['fecha_desde']&&$_GET['fecha_hasta']) {			
			$v->add(new Condition(	$_GET['fecha_hasta'] >= $_GET['fecha_desde'] , "monitoreoTareas.rango.inconsistente"), true);
						
			$hoy = new Fecha();
			$hoy->loadFromNow();			
			$v->add(new Condition( $_GET['fecha_desde'] <= $hoy->dateToString() ,"monitoreoTareas.fechaPosteriorFechaActual"), true);
		}
		
		// Valido que la aplicación se esté ejecutando
		$configuracionDAO = new ConfiguracionDAO();
		$config = $configuracionDAO->getById(CONST_CONFIG_ESTADO_SRDFIP);
		if ($config->valor != ESTADO_SRDFIP_EJECUTANDO) {
			$v->add(new Condition(false,'envioManual.conf.aplicacion.detenida'), false);
		}
	}


	function guardarDatos(){
		// Cargo el objeto sobre el que voy a trabajar		
		if($_GET['id_envio_manual']) $envio = new Envio($_GET['id_envio_manual']);						
		else if($_POST['id_envio_manual']) $envio = new Envio($_POST['id_envio_manual']);
		else {
			$envio = new Envio();
			$envio->baja_logica = FALSE_;
			$envio->temporal = TRUE_;
			$envio->habilitado = FALSE_;
			$envio->manual = TRUE_;
			$envio->save();
		}
		
		
		// Cargo los datos del envio
		$envio->baja_logica = FALSE_;	
		$envio->manual = TRUE_;
		$envio->temporal = TRUE_;
		$envio->inmediato = FALSE_;
		$envio->habilitado = FALSE_;
		$envio->nombre = trim($_GET['nombre_envio_manual']);		
		
		$envio->save();
	
		// Guardo los hosts		
		if($_GET["hosts_agregados"]){			
			foreach($_GET["hosts_agregados"] as $hostAgregado){
				$envio->agregarHost($hostAgregado);																
			}
		} 
		if($_GET["hosts"]) {
			if(is_array($_GET['hosts'])){
				foreach($_GET["hosts"] as $hostNoAgregado){
					$envio->quitarHost($hostNoAgregado);			
				}
			}else{
				printr("hosts no puede ser interpretado como un array");
				printr($_GET['hosts']);
			}
		}
		
		// Y se crean las informaciones de estado correspondientes
		$infoEstadoDAO = new InformacionEstadoDAO();
		$ie = $infoEstadoDAO->filterByField("id_envio",$envio->id);
		if (count($ie) == 0) {
			$informacionEstado = new InformacionEstado();
		} else $informacionEstado = $ie[0];
		$informacionEstado->id_envio = $envio->id;
		$informacionEstado->nombre_estado = "Enviado por envío manual \"".$envio->nombre."\"";
		$informacionEstado->save();
		
		return $envio;
	}

	
	function limpiar(){
		$this->asignar('fecha_desde','');
		$this->asignar('fecha_hasta','');		
	}


	function recargar(){
	}


	function buscar(){		
		// cargo el listado
		$listadoFicherosBusqueda = Listados::create('ListadoFicherosParaSeleccionar', $_GET);
		$this->asignar("listado_ficheros_resultado_busqueda", $listadoFicherosBusqueda->imprimir_listado());

		$this->actualizarPantalla();
	}


	function agregarFicheros() {		
		$tareaDAO = new TareaDAO();
		$idsFicheros = $tareaDAO->getIdsNoAsignadasEnvioManual($_GET['id_envio_manual']);		
		for($i=0; $i<count($idsFicheros['id']); $i++){
			if($_GET['id_fila_'.$idsFicheros['id'][$i]]) {				
				$fichero_env_manual = new FicheroEnvioManual();
				$fichero_env_manual->id_fichero = $idsFicheros['id'][$i];
				$fichero_env_manual->id_envio = $_GET['id_envio_manual'];
				$fichero_env_manual->save();
			}
		}
		$this->actualizarPantalla();		
	}


	function procesar(&$nextAction){
		$envio = $this->guardarDatos();
		$envio->manual = TRUE_;
		$envio->habilitado = TRUE_;
		$envio->inmediato = FALSE_;
		$envio->temporal = FALSE_;
		
		if ($envio->save()) {
			//Se crea una fecha y se obtiene la actual
			$fecha = new Fecha();
			$fecha->loadFromNow();
			//Se le suma x segundos a la hora para q la planificacion se realice en el prox. ciclo del dispacher.
			$fecha->addSeconds(30); //FIXME: sacar el dato de la configuracion del dispacher
							
			//Creo una planificacion y agrego sus datos correspondientes.
			$planificacion = new Planificacion();
			$planificacion->id_recoleccion = "NULL";
			$planificacion->hora = $fecha->hora.":".$fecha->minutos.":".$fecha->segundos;			
			$planificacion->dia_absoluto = $fecha->anio."-".$fecha->mes."-".$fecha->dia; 			
			$planificacion->dia_semana = "NULL";	
			$planificacion->id_envio = $envio->id;
			$planificacion->baja_logica = FALSE_;
			$fechaAyer = new Fecha();
			$fechaAyer->loadFromYesterday();
			$planificacion->fecha_vigencia = $fechaAyer->anio."-".$fechaAyer->mes."-".$fechaAyer->dia;
			
			$res = $planificacion->save();
			
			if($res) {
				$nextAction->setNextAction('Inicio', 'envioManual.conf.lanzada.ok');	
			} else {
				$nextAction->setNextAction('Inicio', 'envioManual.conf.guardado.error',array(error => "1"));
			}
		} else {
			$nextAction->setNextAction('Inicio', 'envioManual.conf.guardado.error',array(error => "1"));
		}
	}


	function actualizarPantalla(){
		// Cargo el objeto sobre el que voy a trabajar
		if($_POST['id_envio_manual']) $envio = new Envio($_POST['id_envio_manual']);			
		else if($_GET['id_envio_manual']) {
			$envio = new Envio($_GET['id_envio_manual']);
			if($_GET['nombre_envio_manual']) $this->asignar("nombre_envio_manual",$_GET['nombre_envio_manual']);
		}
		else {
			$envio = new Envio();
			$envio->baja_logica = FALSE_;
			$envio->temporal = TRUE_;
			$envio->habilitado = FALSE_;
			$envio->inmediato = FALSE_;
			$envio->manual = TRUE_;
			$envio->save();
		}

		// Se cargan los hosts		
		$hosts = ComboHostsSinAsignarEnvioManual($envio->id);
		$hostsAgregados = ComboHostsAsignados($envio->id);		
		
		$this->asignar('ids_hosts', $hosts);
		$this->asignar('ids_hosts_agregados',$hostsAgregados);		
		
		
		// Cargo los datos del envio
		$this->asignar('id_envio_manual',$envio->id);
		$this->asignar('nombre_envio_manual',$envio->nombre);

		// Se carga el listado de ficheros asignados
		$vectorFicheros['id_envio_manual'] = $envio->id;
		$listadoFicheros = Listados::create('ListadoFicherosAsignadosAEnvioManual', $vectorFicheros);
		$this->asignar("listado_ficheros", $listadoFicheros->imprimir_listado());
	}
}

?>