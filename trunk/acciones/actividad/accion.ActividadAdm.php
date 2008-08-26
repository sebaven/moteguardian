<?
include_once BASE_DIR . 'clases/negocio/clase.Central.php';
include_once BASE_DIR . 'clases/negocio/clase.Actividad.php';
include_once BASE_DIR . 'clases/negocio/clase.DatosProceso.php';
include_once BASE_DIR . 'clases/dao/dao.Actividad.php';
include_once BASE_DIR . 'clases/dao/dao.ActividadEnviador.php';
include_once BASE_DIR . 'clases/dao/dao.TecnologiaEnviador.php';
include_once BASE_DIR . 'clases/listados/clase.Listados.php';
include_once BASE_DIR . 'clases/negocio/clase.ActividadEnviador.php';



class ActividadAdm extends Action
{
	var $tpl = "tpl/actividad/tpl.ActividadAdm.php";	

	function inicializar() {
			
		if($_POST['id_actividad']) {
		    $actividad = new Actividad($_POST['id_actividad']);											
			$this->asignarArray($actividad->toArray());
			$this->asignar('filtro', $actividad->filtro_archivos_recoleccion);
			$this->asignar('formato_renombrado_recoleccion', $actividad->formato_renombrado_recoleccion);
			$this->asignar('id_actividad', $_POST['id_actividad']);								
			$id = $_POST['id_actividad'];			
		} else if($_GET['id']) {
			$actividad = new Actividad($_GET['id']);					
			$this->asignarArray($actividad->toArray());
			$this->asignar('filtro', $actividad->filtro_archivos_recoleccion);
			$this->asignar('formato_renombrado_recoleccion', $actividad->formato_renombrado_recoleccion);
			$this->asignar('id_actividad', $_GET['id']);
			$id = $_GET['id'];
		} else {			
			$actividad = new Actividad();
			$actividad->temporal = TRUE_;
			$actividad->save();								
			$this->asignar('id_actividad', $actividad->id);
			$id = $actividad->id;
		}
	
		//Se asigna las Plantillas de Recoleccion
		$this->asignar('comboPlatillaRecoleccion', ComboPlantillaRecoleccion());
    	//Se asigna las Centrales en el Combo de Centrales
        $this->asignar('comboCentrales', ComboCentral());					
		//Genero el listado de la Planificacion para esa Actividad		
		        
        $vector['id'] = $id;
        $listado = Listados::create('ListadoPlanificacion', $vector);
		$this->asignar("listado_planificacion", $listado->imprimir_listado());                        	
		$actividadDAO= new ActividadDAO();
		$cantEnvios=$actividadDAO->getSqlCantEnvios($id);
		$this->asignar('cantEnvios',$cantEnvios);
		
		//Obtengo los id de las actividades_envio y los id de las Tecnologias que tiene dicha Actividad
		$idAETecno=$actividadDAO->getSqlIDAETec($id);
 		//Genero los listados de los Envios de dicha Actividad
 		
 		$var1=(int)$cantEnvios;
		for($np=0;$np<$var1;$np++){
			$this->asignarArray(array('id' => $idAETecno['id'][$np]));
			$listado='listado'.$np;
			$$listado = Listados::create('ListadoPlanificacionEnvio', array('id' => $idAETecno['id'][$np]));
			$nombreListadoPE='listado_pe_'.$np;
			$this->asignar("$nombreListadoPE", $$listado->imprimir_listado());
			$nombre_enviador='nombre_enviador'.$np;
			$this->asignar("$nombre_enviador", $idAETecno['nombre'][$np]);
			$id_enviador='id_enviador'.$np;
			$this->asignar("$id_enviador", $idAETecno['id'][$np]);
			if($idAETecno['inmediato'][$np]){				
				$this->asignar('inmediato'.$np, 'checked');
			} else {
				$this->asignar('inmediato'.$np, '');	
			}
		}
		
		//Se Agrega el Combo para seleccionar el tipo de Tecnologia que se usara para el enviador a agregar
		$this->asignar('comboTecnologiaEnviador', ComboTecnologiaEnviador());
	}
	
	function validar(&$v) {
		$v->add(new Required('nombre', 'actividad.nombre.required'));
		$v->add(new Required('id_central', 'actividad.central.required'));
		$v->add(new Required('id_plantilla', 'actividad.plantilla.required'));
		$v->add(new Required('filtro', 'actividad.filtro.required'));
	}

	function recargar(){
		$actividad = new Actividad($_POST['id_actividad']);
		$actividad->nombre = $_POST['nombre'];
		$actividad->id_central = $_POST['id_central'];
		$actividad->id_plantilla = $_POST['id_plantilla'];
		$actividad->filtro_archivos_recoleccion = $_POST['filtro'];
		$actividad->formato_renombrado_recoleccion = $_POST['formato_renombrado_recoleccion'];
		$actividadDAO = new ActividadDAO();
		$idAETecno=$actividadDAO->getSqlIDAETec($_POST['id_actividad']);
		$var1=(int)$actividadDAO->getSqlCantEnvios($_POST['id_actividad']);
		for($np=0;$np<$var1;$np++){
			$actividadEnvio = new ActividadEnviador($idAETecno['id'][$np]);
			$actividadEnvio->temporal = FALSE_;
			if($_POST["inmediatamente".$idAETecno['id'][$np]]) $actividadEnvio->inmediato = TRUE_;
			else $actividadEnvio->inmediato = FALSE_;			
			$actividadEnvio->save();
		}
		$actividad->save();
	}
	
	function configurarEnviador(){
		$this->recargar();
		$actividadEnviadorDAO = new ActividadEnviadorDAO();		
		$resultado = $actividadEnviadorDAO->existeNombreEnvio($_POST['id_actividad'], $_POST['nombre_enviador']);		
		if( $resultado == 0 ) {
			if($_POST['id_act_env']) {
				$actividadEnviador = new ActividadEnviador($_POST['id_act_env']);
				$tecnologia_envio = new TecnologiaEnviador($actividadEnviador->id_tecnologia_enviador);			
			} else {
				$actividadEnviador = new ActividadEnviador();		
				$actividadEnviador->id_actividad = $_POST['id_actividad'];
				$actividadEnviador->id_tecnologia_enviador = $_POST['id_tecnologia_enviador'];
				$actividadEnviador->nombre = $_POST['nombre_enviador'];
				$actividadEnviador->baja_logica = FALSE_;
				$actividadEnviador->temporal = TRUE_;
				if($_POST['inmediatamente']){
					$actividadEnviador->inmediato = TRUE_ ;
				} else {
					$actividadEnviador->inmediato = FALSE_;
				}
				$actividadEnviador->save();		
				$tecnologia_envio = new TecnologiaEnviador($_POST['id_tecnologia_enviador']);
	
				// Agrego los datos de procesamiento para los enviadores
				$datosProceso = new DatosProceso();
				$datosProceso->id_proceso_actividad_envio = $actividadEnviador->id;
				$datosProceso->id_proceso_central = "NULL";
				$datosProceso->id_proceso_nodo_plantilla = "NULL";
				$datosProceso->nombre_estado = "Enviado por enviador \"".$actividadEnviador->nombre."\"";
				$datosProceso->save();
			}
			
			$this->asignar('acc_envio', $tecnologia_envio->accion);
			$this->asignar('nombre_tecnologia', $tecnologia_envio->nombre_tecnologia);
			$this->asignar('id_actividad_envio',$actividadEnviador->id);			
		} else {
			$this->asignar('errores',PropertiesHelper::GetKey('actividad.actividadEnviador.nombreExiste'));
		}
	}
	
	function procesar(&$nextAction){		
		$actividad = new Actividad($_POST['id_actividad']);
		$actividad->nombre = $_POST['nombre'];
		$actividad->id_central = $_POST['id_central'];
		$actividad->id_plantilla = $_POST['id_plantilla'];
		$actividad->filtro_archivos_recoleccion = $_POST['filtro'];
		$actividad->formato_renombrado_recoleccion = $_POST['formato_renombrado_recoleccion'];
		$actividad->temporal = FALSE_;
		$actividad->baja_logica = FALSE_;		
		
		// Veo si se modific� el inmediato de algun enviador
		$actividadDAO = new ActividadDAO();
		$idAETecno=$actividadDAO->getSqlIDAETec($_POST['id_actividad']);
		$var1=(int)$actividadDAO->getSqlCantEnvios($_POST['id_actividad']);
		for($np=0;$np<$var1;$np++){
			$actividadEnvio = new ActividadEnviador($idAETecno['id'][$np]);
			$actividadEnvio->temporal = FALSE_;
			if($_POST["inmediatamente".$idAETecno['id'][$np]]) $actividadEnvio->inmediato = TRUE_;
			else $actividadEnvio->inmediato = FALSE_;			
			$actividadEnvio->save();
		}
				
		
		if($actividad->save())
			$nextAction->setNextAction("ActividadBuscar", "actividad.modificacion.ok");
		else
			$nextAction->setNextAction("ActividadBuscar", "actividad.modificacion.error");
	}
}
?>
