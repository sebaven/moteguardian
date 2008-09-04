<?
include_once BASE_DIR ."clases/negocio/clase.Ronda.php";
include_once BASE_DIR ."clases/listados/clase.Listados.php";

class RondaAlta extends Action {
    var $tpl = "tpl/ronda/tpl.RondaAlta.php";
    
    function inicializar() 
    {
    	if($_POST['id_ronda']){ // Estoy en un repost 
			$this->guardarDatos();
		}		
		$this->actualizarPantalla();

		// Cargo los combos
		$this->asignar('options_guardias', ComboGuardia());
    }

    
    function guardarDatos()
    {        
    	$ronda = new Ronda($_POST['id_ronda']);
		$ronda->id_guardia = $_POST['id_ronda'];
		return $ronda->save();
    }
    
    function recargar(){}    
    
	function actualizarPantalla()
    {        
    	if($_POST['id_ronda']){ // Estoy en un repost 
    		$ronda = new Ronda($_POST['id_ronda']);
    	} else if($_GET['id_ronda']){ // Estoy en una modificación
    		$ronda = new Ronda($_GET['id_ronda']);    		
    	} else { // Estoy en un alta (lo tendría que crear con estado temporal pero pfff
    		$ronda = new Ronda();
    		$ronda->baja_logica = FALSE_;
    		$ronda->save();    		
    	}
    	$this->asignar('id_ronda', $ronda->id);
    	$this->asignar('id_guardia', $ronda->id_guardia);

    	// Cargo la lista de planificaciones
    	$listadoPlanificaciones = Listados::create("ListadoPlanificacion", $ronda->id);
    	$this->asignar('listado_planificaciones', $listadoPlanificaciones->imprimir_listado());
    	
    	// Cargo la lista de items ronda
		$listadoItemsRonda = Listados::create("ListadoItemsRonda", $ronda->id);
    	$this->asignar('listado_eslabones', $listadoItemsRonda->imprimir_listado());
    	
    }

    
    function procesar(&$nextAction)
    {    	
    	if ($this->guardarDatos()){
            $nextAction->setNextAction('RondaAdm', 'alta.ronda.ok');
        }
        else{
            $nextAction->setNextAction('RondaAdm', 'alta.ronda.error',array(error => "1"));
        }
    }    
}
?>