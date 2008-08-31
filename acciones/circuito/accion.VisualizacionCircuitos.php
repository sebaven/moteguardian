<?
include_once BASE_DIR."clases/dao/dao.Recoleccion.php";
include_once BASE_DIR."clases/dao/dao.Central.php";
include_once BASE_DIR."clases/dao/dao.Planificacion.php";
include_once BASE_DIR."clases/dao/dao.Plantilla.php";
include_once BASE_DIR."clases/dao/dao.Envio.php";

include_once BASE_DIR ."clases/listados/clase.Listados.php";


/**
 * @author cgalli
 */
class VisualizacionCircuitos extends Action{
	var $tpl = "tpl/circuito/tpl.VisualizacionCircuitos.php";	
	
	
	
	function inicializar() {
		$this->asignar('tecnologias_central', ComboTecnologiaCentral(true, ''));
		$this->asignar('tecnologias_recolector', ComboTecnologiaRecoleccion(true, ''));		
	}
	
	function validar(&$v) {
		$this->actualizarPantalla();				
	}
		
	function limpiar() {
	}
	
	function actualizarPantalla() {
		/** Actualizo los datos de la búsqueda */
		$this->asignar('central', trim($_POST['central']));
		$this->asignar('procesador', trim($_POST['procesador']));
		$this->asignar('id_tecnologia_central', $_POST['id_tecnologia_central']);
		$this->asignar('id_tecnologia_recolector', $_POST['id_tecnologia_recolector']);
		$this->asignar('id_tecnologia_enviador', $_POST['id_tecnologia_enviador']);
		$this->asignar('recoleccion', trim($_POST['recoleccion']));
		$this->asignar('actividad', trim($_POST['actividad']));
		$this->asignar('plantilla', trim($_POST['plantilla']));
		$this->asignar('envio', trim($_POST['envio']));
		$this->asignar('host', trim($_POST['host']));		
		
		$iCircuito = 0;
		
		/** OJO!! este siempre tiene que cargarse primero porque no recibe número de circuito inicial */
		if($_POST['circuitos_completos']){
			$this->asignar('circuitos_completos_checked', 'checked');
			$iCircuito = $this->cargarCircuitosCompletos();
		}
		if($_POST['centrales_no_asignadas']){
			$this->asignar('centrales_no_asignadas_checked', 'checked');
			$iCircuito = $this->cargarCircuitosIncompletosCentralesNoAsignadas($iCircuito);
		}
		if($_POST['hosts_no_asignados']){
			$this->asignar('hosts_no_asignados_checked', 'checked');
			$this->cargarHostsNoAsignados();
		}
		if($_POST['plantillas_no_asignadas']){
			$this->asignar('plantillas_no_asignadas_checked', 'checked');
			$iCircuito = $this->cargarCircuitosIncompletosPlantillasSinActividad($iCircuito);
		}
		if($_POST['recolecciones_sin_act_env']){
			$this->asignar('recolecciones_sin_act_env_checked', 'checked');
			$iCircuito = $this->cargarCircuitosIncompletosRecoleccionesSinActividadSinEnvio($iCircuito);
			$iCircuito = $this->cargarCircuitosIncompletosRecoleccionesSinActividadConEnvio($iCircuito);
			$iCircuito = $this->cargarCircuitosIncompletosRecoleccionesConActividadSinEnvio($iCircuito);
		}					
	}
	
	function buscar(){
		$this->actualizarPantalla();					
	}
		
	
	
	function cargarCircuitosCompletos(){
		/** Inicializo los daos que voy a usar */
		$daoRecoleccion = new RecoleccionDAO();
		$daoCentral = new CentralDAO();
		$daoPlanificacion = new PlanificacionDAO();
		$daoNodoPlantilla = new NodoPlantillaDAO();
		$daoEnvio = new EnvioDAO();

		/** Obtengo los circuitos completos */
		$circuitosCompletos = $daoRecoleccion->getCircuitosCompletos($_POST);
		
		/** Inicializo los datos para la iteración */
		$this->asignar('cantidad_circuitos', $circuitosCompletos['cantidad_circuitos'][0]);		
		$iCircuito = 0;		
		
		for($iCircuito; $iCircuito < $circuitosCompletos['cantidad_circuitos'][0]; $iCircuito++ ){
			
			$this->asignar('recoleccion_'.$iCircuito, $circuitosCompletos['nombre_recoleccion'][$iCircuito]);
			$this->asignar('id_recoleccion_'.$iCircuito, $circuitosCompletos['id_recoleccion'][$iCircuito]);
			if($circuitosCompletos['habilitado_recoleccion'][$iCircuito]==TRUE_) $this->asignar('habilitado_checked_'.$iCircuito, 'checked');
			$this->asignar('cantidad_planificaciones_'.$iCircuito, $circuitosCompletos['cantidad_planificaciones'][$iCircuito]);
			$this->asignar('cantidad_centrales_'.$iCircuito, $circuitosCompletos['cantidad_centrales'][$iCircuito]);

			$this->asignar('plantilla_'.$iCircuito, $circuitosCompletos['nombre_plantilla'][$iCircuito]);						
			$this->asignar('id_plantilla_'.$iCircuito, $circuitosCompletos['id_plantilla'][$iCircuito]);						
			$this->asignar('cantidad_filtros_'.$iCircuito, $circuitosCompletos['cantidad_filtros'][$iCircuito]);
			
			$this->asignar('cantidad_envios_'.$iCircuito, $circuitosCompletos['cantidad_envios'][$iCircuito]);
			
			
			// Cargo las centrales
			$centrales = $daoCentral->getCentralesByRecoleccion($circuitosCompletos['id_recoleccion'][$iCircuito]);

			for($iCentral = 0; $iCentral < $circuitosCompletos['cantidad_centrales'][$iCircuito]; $iCentral++ ) {
				$this->asignar('nombre_central_'.$iCircuito."_".$iCentral, $centrales[$iCentral]->nombre);
				$this->asignar('procesador_central_'.$iCircuito."_".$iCentral, $centrales[$iCentral]->procesador);
				$this->asignar('id_central_'.$iCircuito."_".$iCentral, $centrales[$iCentral]->id);				
			}
			
			
			// Cargo las planificaciones
			$planificaciones = $daoPlanificacion->getPlanificacionesDeRecoleccion($circuitosCompletos['id_recoleccion'][$iCircuito]);
					
			for($iPlanificacion = 0; $iPlanificacion < $circuitosCompletos['cantidad_planificaciones'][$iCircuito]; $iPlanificacion++ ){
				$this->asignar('planificacion_'.$iCircuito."_".$iPlanificacion, $planificaciones[$iPlanificacion]->toString());
			}
			
			
			// Cargo los filtros
			$filtros = $daoNodoPlantilla->getByIdPlantilla($circuitosCompletos['id_plantilla'][$iCircuito]);

			for($iFiltro = 0; $iFiltro < $circuitosCompletos['cantidad_filtros'][$iCircuito]; $iFiltro++ ){
				$this->asignar('filtro_'.$iCircuito."_".$iFiltro, $filtros[$iFiltro]->nombre);
			}
			
			
			// Cargo los envios
			$envios = $daoEnvio->getByIdRecoleccion($circuitosCompletos['id_recoleccion'][$iCircuito]);
						
			for($iEnvio = 0; $iEnvio < $circuitosCompletos['cantidad_envios'][$iCircuito]; $iEnvio++ ){
				$this->asignar('envio_'.$iCircuito."_".$iEnvio, $envios[$iEnvio]->nombre);
				$this->asignar('id_envio_'.$iCircuito."_".$iEnvio, $envios[$iEnvio]->id);
				if($envios[$iEnvio]->habilitado==TRUE_) $this->asignar("habilitado_envio_checked_".$iCircuito."_".$iEnvio, 'checked');
			}			
		}
		
		return $iCircuito;
	}
	
	function cargarCircuitosIncompletosCentralesNoAsignadas($iCircuitoInicial){
		$daoCentral = new CentralDAO();
		
		$circuitosCentrales = $daoCentral->getCircuitosIncompletosCentralesNoAsignadas($_POST);
				
		$iCircuito = $iCircuitoInicial;		
		for( $iCircuito; $iCircuito < $iCircuitoInicial + count($circuitosCentrales); $iCircuito++ ){						
			$this->asignar('recoleccion_'.$iCircuito, 'no asignada');
			$this->asignar('habilitado_checked_'.$iCircuito, 'no asignado');
			$this->asignar('plantilla_'.$iCircuito, 'no asignada');									
			$this->asignar('cantidad_centrales_'.$iCircuito, '1');
			$this->asignar('cantidad_planificaciones_'.$iCircuito, '1');
			$this->asignar('cantidad_filtros_'.$iCircuito, '1');
			$this->asignar('cantidad_envios_'.$iCircuito, '1');
			
			$this->asignar('nombre_central_'.$iCircuito."_0", $circuitosCentrales[$iCircuito-$iCircuitoInicial]->nombre);
			$this->asignar('id_central_'.$iCircuito."_0", $circuitosCentrales[$iCircuito-$iCircuitoInicial]->id);
			$this->asignar('procesador_central_'.$iCircuito."_0", $circuitosCentrales[$iCircuito-$iCircuitoInicial]->procesador);			
						
			$this->asignar('planificacion_'.$iCircuito."_0", 'no asignada');
			
			$this->asignar('filtro_'.$iCircuito."_0", 'no asignado');
			
			$this->asignar('envio_'.$iCircuito."_0", 'no asignado');			
			$this->asignar("habilitado_envio_checked_".$iCircuito."_0", 'no asignado');
		}
				
		$this->asignar('cantidad_circuitos', $iCircuito);
		return $iCircuito;
	}

	function cargarCircuitosIncompletosRecoleccionesSinActividadSinEnvio($iCircuitoInicial) {
		$daoRecoleccion = new RecoleccionDAO();
		$daoCentral = new CentralDAO();
		$daoPlanificacion = new PlanificacionDAO();
		
		$circuitosRecolecciones = $daoRecoleccion->getCircuitosIncompletosRecoleccionesSinActividadSinEnvio($_POST);
		
		$iCircuito = $iCircuitoInicial;			
		for( $iCircuito; $iCircuito < $iCircuitoInicial + count($circuitosRecolecciones); $iCircuito++ ){						
			
			$this->asignar('recoleccion_'.$iCircuito, $circuitosRecolecciones[$iCircuito-$iCircuitoInicial]->nombre_recoleccion);
			$this->asignar('plantilla_'.$iCircuito, 'no asignada');						
			$this->asignar('id_recoleccion_'.$iCircuito, $circuitosRecolecciones[$iCircuito-$iCircuitoInicial]->id_recoleccion);
			if($circuitosRecolecciones[$iCircuito-$iCircuitoInicial]->habilitado_recoleccion==TRUE_) $this->asignar('habilitado_checked_'.$iCircuito, 'checked');
			
			$this->asignar('cantidad_filtros_'.$iCircuito, '1');
			$this->asignar('cantidad_envios_'.$iCircuito, '1');
						
			// Cargo las centrales
			$centrales = $daoCentral->getCentralesByRecoleccion($circuitosRecolecciones[$iCircuito-$iCircuitoInicial]->id_recoleccion);
			$this->asignar('cantidad_centrales_'.$iCircuito, count($centrales));			
			for($iCentral = 0; $iCentral < count($centrales); $iCentral++ ) {
				$this->asignar('nombre_central_'.$iCircuito."_".$iCentral, $centrales[$iCentral]->nombre);
				$this->asignar('id_central_'.$iCircuito."_".$iCentral, $centrales[$iCentral]->id);
				$this->asignar('procesador_central_'.$iCircuito."_".$iCentral, $centrales[$iCentral]->procesador);				
			}
			
			
			// Cargo las planificaciones
			$planificaciones = $daoPlanificacion->getPlanificacionesDeRecoleccion($circuitosRecolecciones[$iCircuito-$iCircuitoInicial]->id_recoleccion);
			$this->asignar('cantidad_planificaciones_'.$iCircuito, count($planificaciones));		
			for($iPlanificacion = 0; $iPlanificacion < count($planificaciones); $iPlanificacion++ ){
				$this->asignar('planificacion_'.$iCircuito."_".$iPlanificacion, $planificaciones[$iPlanificacion]->toString());
			}
			
			$this->asignar('filtro_'.$iCircuito."_0", 'no asignado');
			
			$this->asignar('envio_'.$iCircuito."_0", 'no asignado');			
			$this->asignar("habilitado_envio_checked_".$iCircuito."_0", 'no asignado');
			
									
		}
		
		$this->asignar('cantidad_circuitos', $iCircuito);
		return $iCircuito;
	}
	
	function cargarCircuitosIncompletosRecoleccionesSinActividadConEnvio($iCircuitoInicial) {
		$daoRecoleccion = new RecoleccionDAO();
		$daoCentral = new CentralDAO();
		$daoPlanificacion = new PlanificacionDAO();
		$daoEnvio = new EnvioDAO();
		
		$circuitosRecolecciones = $daoRecoleccion->getCircuitosIncompletosRecoleccionesSinActividadConEnvio($_POST);
		
		$iCircuito = $iCircuitoInicial;			
		for( $iCircuito; $iCircuito < $iCircuitoInicial + count($circuitosRecolecciones); $iCircuito++ ){						
					
			$this->asignar('recoleccion_'.$iCircuito, $circuitosRecolecciones[$iCircuito-$iCircuitoInicial]->nombre_recoleccion);
			$this->asignar('plantilla_'.$iCircuito, 'no asignada');			
			$this->asignar('id_recoleccion_'.$iCircuito, $circuitosRecolecciones[$iCircuito-$iCircuitoInicial]->id_recoleccion);
			if($circuitosRecolecciones[$iCircuito-$iCircuitoInicial]->habilitado_recoleccion==TRUE_) $this->asignar('habilitado_checked_'.$iCircuito, 'checked');
			
			$this->asignar('cantidad_filtros_'.$iCircuito, '1');
									
			// Cargo las centrales
			$centrales = $daoCentral->getCentralesByRecoleccion($circuitosRecolecciones[$iCircuito-$iCircuitoInicial]->id_recoleccion);
			$this->asignar('cantidad_centrales_'.$iCircuito, count($centrales));			
			for($iCentral = 0; $iCentral < count($centrales); $iCentral++ ) {
				$this->asignar('nombre_central_'.$iCircuito."_".$iCentral, $centrales[$iCentral]->nombre);
				$this->asignar('id_central_'.$iCircuito."_".$iCentral, $centrales[$iCentral]->id);
				$this->asignar('procesador_central_'.$iCircuito."_".$iCentral, $centrales[$iCentral]->procesador);				
			}
			
			
			// Cargo las planificaciones
			$planificaciones = $daoPlanificacion->getPlanificacionesDeRecoleccion($circuitosRecolecciones[$iCircuito-$iCircuitoInicial]->id_recoleccion);
			$this->asignar('cantidad_planificaciones_'.$iCircuito, count($planificaciones));		
			for($iPlanificacion = 0; $iPlanificacion < count($planificaciones); $iPlanificacion++ ){
				$this->asignar('planificacion_'.$iCircuito."_".$iPlanificacion, $planificaciones[$iPlanificacion]->toString());
			}
			
			$this->asignar('filtro_'.$iCircuito."_0", 'no asignado');
									
			// Cargo los envios			
			$envios = $daoEnvio->getByIdRecoleccion($circuitosRecolecciones[$iCircuito-$iCircuitoInicial]->id_recoleccion);
			$this->asignar('cantidad_envios_'.$iCircuito, count($envios));			
			for($iEnvio = 0; $iEnvio < count($envios); $iEnvio++ ){				
				$this->asignar('envio_'.$iCircuito."_".$iEnvio, $envios[$iEnvio]->nombre);
				$this->asignar('id_envio_'.$iCircuito."_".$iEnvio, $envios[$iEnvio]->id);
				if($envios[$iEnvio]->habilitado==TRUE_) $this->asignar("habilitado_envio_checked_".$iCircuito."_".$iEnvio, 'checked');
			}	
									
		}
		
		$this->asignar('cantidad_circuitos', $iCircuito);
		return $iCircuito;
	}
	
	function cargarCircuitosIncompletosRecoleccionesConActividadSinEnvio($iCircuitoInicial) {
		$daoRecoleccion = new RecoleccionDAO();
		$daoCentral = new CentralDAO();
		$daoPlanificacion = new PlanificacionDAO();
		$daoNodoPlantilla = new NodoPlantillaDAO();
		
		$circuitosRecolecciones = $daoRecoleccion->getCircuitosIncompletosRecoleccionesConActividadSinEnvio($_POST);
		
		$iCircuito = $iCircuitoInicial;			
		for( $iCircuito; $iCircuito < $iCircuitoInicial + count($circuitosRecolecciones); $iCircuito++ ){						
			
			$this->asignar('recoleccion_'.$iCircuito, $circuitosRecolecciones[$iCircuito-$iCircuitoInicial]->nombre_recoleccion);
			$this->asignar('plantilla_'.$iCircuito, $circuitosRecolecciones[$iCircuito-$iCircuitoInicial]->nombre_plantilla);
			$this->asignar('id_plantilla_'.$iCircuito, $circuitosRecolecciones[$iCircuito-$iCircuitoInicial]->id_plantilla);			
			$this->asignar('id_recoleccion_'.$iCircuito, $circuitosRecolecciones[$iCircuito-$iCircuitoInicial]->id_recoleccion);
			if($circuitosRecolecciones[$iCircuito-$iCircuitoInicial]->habilitado_recoleccion==TRUE_) $this->asignar('habilitado_checked_'.$iCircuito, 'checked');
			
			$this->asignar('cantidad_envios_'.$iCircuito, '1');
						
			// Cargo las centrales
			$centrales = $daoCentral->getCentralesByRecoleccion($circuitosRecolecciones[$iCircuito-$iCircuitoInicial]->id_recoleccion);
			$this->asignar('cantidad_centrales_'.$iCircuito, count($centrales));			
			for($iCentral = 0; $iCentral < count($centrales); $iCentral++ ) {
				$this->asignar('nombre_central_'.$iCircuito."_".$iCentral, $centrales[$iCentral]->nombre);
				$this->asignar('id_central_'.$iCircuito."_".$iCentral, $centrales[$iCentral]->id);
				$this->asignar('procesador_central_'.$iCircuito."_".$iCentral, $centrales[$iCentral]->procesador);				
			}
			
			
			// Cargo las planificaciones
			$planificaciones = $daoPlanificacion->getPlanificacionesDeRecoleccion($circuitosRecolecciones[$iCircuito-$iCircuitoInicial]->id_recoleccion);
			$this->asignar('cantidad_planificaciones_'.$iCircuito, count($planificaciones));		
			for($iPlanificacion = 0; $iPlanificacion < count($planificaciones); $iPlanificacion++ ){
				$this->asignar('planificacion_'.$iCircuito."_".$iPlanificacion, $planificaciones[$iPlanificacion]->toString());
			}
			
			
			// 	Cargo los filtros
			$filtros = $daoNodoPlantilla->getByIdPlantilla($circuitosRecolecciones[$iCircuito-$iCircuitoInicial]->id_plantilla);
			$this->asignar('cantidad_filtros_'.$iCircuito, count($filtros));
						
			for($iFiltro = 0; $iFiltro < count($filtros); $iFiltro++ ){				
				$this->asignar('filtro_'.$iCircuito."_".$iFiltro, $filtros[$iFiltro]->nombre);
			}
			
			$this->asignar('envio_'.$iCircuito."_0", 'no asignado');			
			$this->asignar("habilitado_envio_checked_".$iCircuito."_0", 'no asignado');
		}
		
		$this->asignar('cantidad_circuitos', $iCircuito);
		return $iCircuito;
	}
	
	function cargarCircuitosIncompletosPlantillasSinActividad($iCircuitoInicial) {
		$daoPlantilla = new PlantillaDAO();
		$plantillas = $daoPlantilla->getCircuitosIncompletosPlantillaSinActividad($_POST);
		$daoNodoPlantilla = new NodoPlantillaDAO();
		
		$iCircuito = $iCircuitoInicial;
		$iPlantilla = 0;		
		for($iCircuito; $iCircuito < count($plantillas)+$iCircuitoInicial; $iCircuito++ ){						
			$this->asignar('recoleccion_'.$iCircuito, 'no asignada');
			$this->asignar('plantilla_'.$iCircuito, $plantillas[$iPlantilla]->nombre);			
			$this->asignar('id_plantilla_'.$iCircuito, $plantillas[$iPlantilla]->id);			
			$this->asignar('habilitado_checked_'.$iCircuito, 'no asignado');
			$this->asignar('cantidad_centrales_'.$iCircuito, '1');
			$this->asignar('cantidad_planificaciones_'.$iCircuito, '1');			
			$this->asignar('cantidad_envios_'.$iCircuito, '1');

			$this->asignar('nombre_central_'.$iCircuito."_0", 'no asignado');
			$this->asignar('id_central_'.$iCircuito."_0", 'no asignado');
			$this->asignar('procesador_central_'.$iCircuito."_0", 'no asignado');				
						
			$this->asignar('planificacion_'.$iCircuito."_0", 'no asignado');
					
			// Cargo los filtros
			$filtros = $daoNodoPlantilla->getByIdPlantilla($plantillas[$iPlantilla]->id);
			$this->asignar('cantidad_filtros_'.$iCircuito, count($filtros));
			for($iFiltro = 0; $iFiltro < count($filtros); $iFiltro++ ){
				$this->asignar('filtro_'.$iCircuito."_".$iFiltro, $filtros[$iFiltro]->nombre);
			}
					
			$this->asignar('envio_'.$iCircuito."_0", 'no asignado');			
			$this->asignar("habilitado_envio_checked_".$iCircuito."_0", 'no asignado');
				
			$iPlantilla++;	
		}
		
		$this->asignar('cantidad_circuitos', $iCircuito);
		return $iCircuito;		
	}
	
	function cargarHostsNoAsignados(){
		$parametros['id_envio'] = 'NULL';
		$listado = Listados::create('ListadoHosts', $parametros);
		$this->asignar("listado", $listado->imprimir_listado());
	}
}
?>