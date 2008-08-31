<?
// Clases de negocio
include_once BASE_DIR . 'clases/negocio/clase.Central.php';
include_once BASE_DIR."clases/negocio/clase.InformacionEstado.php";

// Daos
include_once BASE_DIR . 'clases/dao/dao.Envio.php';
include_once BASE_DIR . 'clases/dao/dao.TecnologiaEnviador.php';
include_once BASE_DIR . 'clases/dao/dao.InformacionEstado.php';

// Listados
include_once BASE_DIR . 'clases/listados/clase.Listados.php';

class EnvioAdm extends Action {
	var $tpl = "tpl/envio/tpl.EnvioAdm.php";	

	function inicializar() {
		// Combos que se cargan siempre
		$this->asignar('opciones_recolecciones', ComboRecoleccion(true,''));
		$this->actualizarPagina();		
	}
	
	
	function validar(&$v) {
		// Los datos se guardan acá porque es necesario que se guarden los host antes de verificar si hay
		// asociados al envío en la base de datos. 			
		if($_POST['id_envio']) $this->guardarDatos();
		
		$v->add(new Required('nombre_envio', 'envio.nombre.required'),false);
		$v->add(new Required('recoleccion', 'envio.recoleccion.required'),false);
		
		$hostDAO = new HostDAO();
		$hostsAgregados = $hostDAO->getHostsAsignadosAEnvio($_POST['id_envio']);	
		if(count($hostsAgregados)<=0) {			
			$v->add(new Condition(false,'envio.host.obligatorio'),false);			
		}
				
		$envioDAO = new EnvioDAO();
		if( count($envioDAO->getNombreEnvioMenosElPasadoPorParametro($_POST['id_envio'], trim($_POST['nombre_envio']))) != 0 ) {
			$v->add(new Condition(false,'envio.conf.nombre.duplicado'), false);
		}		
	}
	
	
	function recargar() {		
	}
	
	
	function actualizarPagina() {		
		// Cargo el objeto sobre el que voy a trabajar
		if($_POST['id_envio']) $envio = new Envio($_POST['id_envio']);			
		else if($_GET['id_envio']) $envio = new Envio($_GET['id_envio']);
		else {
			$envio = new Envio();
			$envio->baja_logica = FALSE_;
			$envio->temporal = TRUE_;
			$envio->habilitado = FALSE_;
			$envio->inmediato = FALSE_;
			$envio->manual = FALSE_;
			$envio->save();
		}
		
		// Asigno la recolección seleccionada y las centrales asociadas a la recolección seleccionada 
		$this->asignar('centrales_de_la_recoleccion', ComboCentralesDeRecoleccion($envio->id_recoleccion));
		$this->asignar('id_recoleccion_seleccionada', $envio->id_recoleccion);
				
		//Asigno los datos en envio
		$this->asignar('id_envio', $envio->id);
		$this->asignar('nombre_envio', $envio->nombre);
		if($envio->habilitado==TRUE_) $this->asignar('habilitado_envio_checked', 'checked');
		if($envio->inmediato==TRUE_) $this->asignar('inmediato_envio_checked', 'checked');
		
		
		//Cargo el listado de planificaciones
		// Planificaciones
		$vector['id'] = $envio->id;		
		$listado = Listados::create('ListadoPlanificacionEnvio', $vector);		
		$this->asignar("listado_planificaciones", $listado->imprimir_listado());
		
		// Se cargan los hosts 
		$hosts = ComboHostsSinAsignar($envio->id);
		$hostsAgregados = ComboHostsAsignados($envio->id);
		
		$this->asignar('ids_hosts', $hosts);
		$this->asignar('ids_hosts_agregados',$hostsAgregados);		
	}

	
	function guardarDatos() {		
		// Cargo el objeto sobre el que voy a trabajar
		if($_GET['id_envio']) $envio = new Envio($_GET['id_envio']);			
		else $envio = new Envio($_POST['id_envio']);
		
		// Cargo los datos del envio
		$envio->nombre = trim($_POST['nombre_envio']);
		$envio->baja_logica = FALSE_;				
		$envio->manual = FALSE_;
		if($_POST['inmediato_envio']) $envio->inmediato = TRUE_;
		else $envio->inmediato = FALSE_;
		if($_POST['habilitado_envio']) $envio->habilitado = TRUE_;
		else $envio->habilitado = FALSE_;
		$envio->nombre = trim($_POST['nombre_envio']);		
		if($_POST['recoleccion']) $envio->id_recoleccion = addslashes($_POST['recoleccion']);
		else $envio->id_recoleccion = 'NULL';
		
		$envio->save();		
				
		// Guardo los hosts		
		if($_POST["hosts_agregados"]){			
			foreach($_POST["hosts_agregados"] as $hostAgregado){
				$envio->agregarHost($hostAgregado);																				
			}
		} 
		if($_POST["hosts"]) {
			foreach($_POST["hosts"] as $hostNoAgregado){
				$envio->quitarHost($hostNoAgregado);							
			}
		}
		
		// Y se crean las informaciones de estado correspondientes
		$infoEstadoDAO = new InformacionEstadoDAO();
		$ie = $infoEstadoDAO->filterByField("id_envio",$envio->id);
		if (count($ie) == 0) {
			$informacionEstado = new InformacionEstado();
		} else $informacionEstado = $ie[0];
		$informacionEstado->id_envio = $envio->id;
		$informacionEstado->nombre_estado = "Enviado por envío \"".$envio->nombre."\"";
		$informacionEstado->save();
		
		return $envio;
	}
	
	function procesar(&$nextAction){		
		$envio = $this->guardarDatos();
		$envio->temporal = FALSE_;
		
		
		if($envio->save()) {
			$nextAction->setNextAction('EnvioBuscar', 'envio.conf.guardado.ok');				
		} else {
			$nextAction->setNextAction('EnvioBuscar', 'envio.conf.guardado.error');
		}
	}
}
?>
