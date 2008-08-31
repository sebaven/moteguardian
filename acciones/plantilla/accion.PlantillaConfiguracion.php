<?php
include_once BASE_DIR ."clases/listados/clase.ListadoFiltros.php";
include_once BASE_DIR ."clases/listados/clase.Listados.php";

include_once BASE_DIR ."clases/negocio/clase.Plantilla.php";
include_once BASE_DIR ."clases/negocio/clase.TipoFiltro.php";
include_once BASE_DIR ."clases/negocio/clase.NodoPlantilla.php";
include_once BASE_DIR ."clases/negocio/clase.InformacionEstado.php";

include_once BASE_DIR ."clases/dao/dao.Plantilla.php";
include_once BASE_DIR ."clases/dao/dao.NodoPlantilla.php";
include_once BASE_DIR .'clases/dao/dao.InformacionEstado.php';

/**
 * @author cgalli
 */
class PlantillaConfiguracion extends Action {
	var $tpl = "tpl/plantilla/tpl.PlantillaConfiguracion.php";		
	
	function inicializar() {				
		// Cargo el combo con los tipos de filtro
		$this->asignar('options_tipo_filtro', ComboTipoFiltro(true,  PropertiesHelper::GetKey('plantillaAlta.seleccionarTipoFiltro')));
		
		if($_POST['id_plantilla']) $this->guardarDatos();
		$this->actualizarPagina();		
	}
	
	function recargar(){	
	}

	function configurarFiltro(){
		$nodoPlantillaDAO = new NodoPlantillaDAO();
		$resultadosvalidador = $nodoPlantillaDAO->validarNombre(trim($_POST['nombre_filtro']), $_POST['id_plantilla']);
		if($resultadosvalidador){			
			$this->asignar('errores', PropertiesHelper::GetKey('validador.nombre.existente'));
		}else{
			$tipo_filtro = new TipoFiltro($_POST['tipo_filtro']);								
			
			$nodoPlantilla = new NodoPlantilla(); // Creo un nodo plantilla				
			$nodoPlantilla->nombre = trim($_POST['nombre_filtro']);
			$nodoPlantilla->id_plantilla = $_POST['id_plantilla'];
			$nodoPlantilla->id_tipo_filtro = $_POST['tipo_filtro'];
			$nodoPlantilla->baja_logica = FALSE_;				
			$PlantillaDAO = new PlantillaDAO();	
			$nodoPlantilla->id_nodo_plantilla_anterior = $PlantillaDAO->getUltimoIdNodoPlantilla($_POST['id_plantilla']);		
			$nodoPlantilla->save();	// Lo guardo		
			$this->asignar('id_nodo_plantilla', $nodoPlantilla->id);
			$this->asignar('acc_filtro', $tipo_filtro->accion);

			// CREACIÓN DE LOS DATOS PARA EL INFORME DEL PROCESAMIENTO DE UN ARCHIVO POR ESTE FILTRO
			$infoEstadoDAO = new InformacionEstadoDAO();
			$ie = $infoEstadoDAO->filterByField("id_recoleccion",$recoleccion->id);
			if (count($ie) == 0) {
				$informacionEstado = new InformacionEstado();
			} else $informacionEstado = $ie[0];
			$informacionEstado->id_nodo_plantilla = $nodoPlantilla->id;
			$informacionEstado->id_central = "NULL";
			$informacionEstado->id_actividad_envio = "NULL";
			$informacionEstado->nombre_estado = "Procesado por filtro \"".$nodoPlantilla->nombre."\"";
			$informacionEstado->save();
		}
	}
	
	function validar(&$v){	
		$v->add(new Required('nombre_plantilla', 'plantilla.nombre.required'));
		
		// Valida que el nombre no esté duplicado 
		$plantillaDAO = new PlantillaDAO();
		$resultados = $plantillaDAO->filterByField('nombre', trim($_POST['nombre_plantilla']));
		if(($resultados && !isset($_GET['id_plantilla'])) || (isset($_GET['id_plantilla']) && $resultados && ($resultados[0]->id != $_GET['id_plantilla']))){
			$v->add(new Condition(false, 'plantilla.nombre.existente'));
		}
		
		// Valida que se haya agregado al menos un filtro
		$nodoPlantillaDAO = new NodoPlantillaDAO();
		$nodosAgregados = $nodoPlantillaDAO->getByIdPlantilla($_POST['id_plantilla']);		
		if(count($nodosAgregados)<=0) {
			$v->add(new Condition(false, 'plantilla.filtro.required'),false);
		}			
	}
	
	function procesar(&$nextAction){	
		$plantilla = $this->guardarDatos();
		$plantilla->temporal = FALSE_;
				 
		if($plantilla->save()) {
			$nextAction->setNextAction('Inicio', 'plantilla.configuracion.ok');
		} else {
			$nextAction->setNextAction('Inicio', 'plantilla.configuracion.error',array(error => "1"));
		}
	}
	
	function guardarDatos() {
		$plantilla = new Plantilla($_POST['id_plantilla']);			
		$plantilla->baja_logica = FALSE_;		
		if($_POST['nombre_plantilla']) $plantilla->nombre = trim($_POST['nombre_plantilla']);
		else $plantilla->nombre = 'NULL';			
		
		$plantilla->save();
		
		return $plantilla;
	}
	
	function actualizarPagina() {
		// Creo el objeto sobre el cual se va a trabajar
		if($_POST['id_plantilla']) {
			$plantilla = new Plantilla ($_POST['id_plantilla']);
		} else if($_GET['id_plantilla']) {
			$plantilla = new Plantilla($_GET['id_plantilla']);
		} else {		
			$plantilla = new Plantilla();				
			$plantilla->temporal = TRUE_;
			$plantilla->baja_logica = FALSE_;
			$plantilla->save();						
		}
		
		$this->asignar('id_plantilla', $plantilla->id);
		$this->asignar('nombre_plantilla', $plantilla->nombre);		
		
		// Cargo el listado de filtros que tiene la plantilla
		$listado = Listados::create('ListadoFiltros');		
		$this->asignar("listado", $listado->imprimir_listado());
	}
	
}
?>