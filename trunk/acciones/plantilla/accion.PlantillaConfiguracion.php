<?php
include_once BASE_DIR ."clases/listados/clase.ListadoFiltros.php";
include_once BASE_DIR ."clases/listados/clase.Listados.php";

include_once BASE_DIR ."clases/negocio/clase.Plantilla.php";
include_once BASE_DIR ."clases/negocio/clase.TipoFiltro.php";
include_once BASE_DIR ."clases/negocio/clase.NodoPlantilla.php";
include_once BASE_DIR ."clases/negocio/clase.DatosProceso.php";

include_once BASE_DIR ."clases/dao/dao.Plantilla.php";
include_once BASE_DIR ."clases/dao/dao.NodoPlantilla.php";

/**
 * @author cgalli
 */
class PlantillaConfiguracion extends Action
{
	var $tpl = "tpl/plantilla/tpl.PlantillaConfiguracion.php";		
	
	function inicializar() {		
		if($_POST['id_plantilla']) {
			$this->asignar('id_plantilla', $_POST['id_plantilla']);
			$this->asignar('nombre_plantilla', $_POST['nombre_plantilla']);
			$this->asignar('id_tipo_filtro', $_POST['tipo_filtro']);			
		} else{
			if($_GET['id_plantilla']) {
				$plantilla = new Plantilla($_GET['id_plantilla']);				
				$this->asignar('id_plantilla', $plantilla->id);
				if($_GET['nombre_plantilla']) $this->asignar('nombre_plantilla', $_GET['nombre_plantilla']);
				else $this->asignar('nombre_plantilla', $plantilla->nombre);
			} else {		
				$plantilla = new Plantilla();				
				$plantilla->temporal = TRUE_;
				$plantilla->save();
				$this->asignar('id_plantilla',$plantilla->id);				
			}
		}							
				
		// Cargo el combo con los tipos de filtro
		$this->asignar('options_tipo_filtro', ComboTipoFiltro(true,  PropertiesHelper::GetKey('plantillaAlta.seleccionarTipoFiltro')));
				
		// Cargo el listado de filtros que tiene la plantilla
		$listado = Listados::create('ListadoFiltros');		
		$this->asignar("listado", $listado->imprimir_listado());			
	}
	
	function recargar(){	
	}

	function configurarFiltro(){
		$nodoPlantillaDAO = new NodoPlantillaDAO();
		$resultadosvalidador = $nodoPlantillaDAO->validarNombre($_POST['nombre_filtro'], $_POST['id_plantilla']);
		if($resultadosvalidador){			
			$this->asignar('errores', PropertiesHelper::GetKey('validador.nombre.existente'));
		}else{
			$tipo_filtro = new TipoFiltro($_POST['tipo_filtro']);								
			
			$nodoPlantilla = new NodoPlantilla(); // Creo un nodo plantilla				
			$nodoPlantilla->nombre = $_POST['nombre_filtro'];
			$nodoPlantilla->id_plantilla = $_POST['id_plantilla'];
			$nodoPlantilla->id_tipo_filtro = $_POST['tipo_filtro'];
			$nodoPlantilla->baja_logica = FALSE_;				
			$PlantillaDAO = new PlantillaDAO();	
			$nodoPlantilla->id_nodo_plantilla_anterior = $PlantillaDAO->getUltimoIdNodoPlantilla($_POST['id_plantilla']);		
			$nodoPlantilla->save();	// Lo guardo		
			$this->asignar('id_nodo_plantilla', $nodoPlantilla->id);
			$this->asignar('acc_filtro', $tipo_filtro->accion);

			// CREACIÓN DE LOS DATOS PARA EL INFORME DEL PROCESAMIENTO DE UN ARCHIVO POR ESTE FILTRO
			$datosProceso = new DatosProceso();
			$datosProceso->id_proceso_nodo_plantilla = $nodoPlantilla->id;
			$datosProceso->id_proceso_central = "NULL";
			$datosProceso->id_proceso_actividad_envio = "NULL";
			$datosProceso->nombre_estado = "Procesado por filtro \"".$nodoPlantilla->nombre."\"";
			$datosProceso->save();
		}
	}
	
	function validar(&$v){	
		$v->add(new Required('nombre_plantilla', 'plantilla.nombre.required'));
		
		$plantillaDAO = new PlantillaDAO();
		$resultados = $plantillaDAO->filterByField('nombre', $_POST['nombre_plantilla']);
		if($resultados&&!isset($_GET['id_plantilla'])){
			$v->add(new Condition(false, 'plantilla.nombre.existente'));
		}		 								
	}
	
	function procesar(&$nextAction){		
		$plantilla = new Plantilla($_POST['id_plantilla']);
		$plantilla->temporal = FALSE_;
		$plantilla->baja_logica = FALSE_;
		$plantilla->nombre = $_POST['nombre_plantilla'];
				 
		if($plantilla->save()) {
			$nextAction->setNextAction('Inicio', 'plantilla.configuracion.ok');
		} else {
			$nextAction->setNextAction('Inicio', 'plantilla.configuracion.error');
		}
	}
}
?>