<?
include_once BASE_DIR ."clases/negocio/clase.ValidadorDuplicados.php";
include_once BASE_DIR ."clases/dao/dao.Plantilla.php";
include_once BASE_DIR ."clases/dao/dao.ValidadorDuplicados.php";
include_once BASE_DIR ."clases/negocio/clase.NodoPlantilla.php";


/**
 * Acci�n en la que se cambia el password del usuario que est� logueado actualmente en el sistema.
 */
class ValidadorDuplicadosConf extends Action 
{
	var $tpl = "tpl/plantilla/tpl.ValidadorDuplicados.php";

	function inicializar()
	{
		if($_GET['id_nodo_plantilla']) {
			$validadorDuplicadosDAO = new ValidadorDuplicadosDAO();			
			$filtros = $validadorDuplicadosDAO->filterByField('id_nodo_plantilla',$_GET['id_nodo_plantilla']);
			$filtro = $filtros[0];			
			$this->asignar('configuracion', $filtro->configuracion);
			$this->asignar('id_nodo_plantilla', $_GET['id_nodo_plantilla']);
		}		
	}
	
	function validar(&$v) {		
		$v->add(new Required('configuracion', 'validador.duplicados.configuracion.required'));
	}

	function guardarValidadorDuplicados(){				
		$validadorDuplicados = new ValidadorDuplicados();			
		$validadorDuplicados->id_nodo_plantilla = $_POST['id_nodo_plantilla'];
		$validadorDuplicados->configuracion = $_POST['configuracion'];		
		return $validadorDuplicados->save();	
	}
	
	function procesar(&$nextAction)
	{
		$parametros['pop']=1;				
		
		if ($this->guardarValidadorDuplicados()){
			$nextAction->setNextAction('SalidaPopupValidadorDuplicados','guardarFiltro.ok',$parametros);
		}
		else {
			$nextAction->setNextAction('SalidaPopupValidadorDuplicados','guardarFiltro.error', $parametros);
		}
	}

}
?>