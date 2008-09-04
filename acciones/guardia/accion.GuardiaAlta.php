<?
include_once BASE_DIR ."clases/negocio/clase.Guardia.php";
include_once BASE_DIR ."clases/listados/clase.Listados.php";

class GuardiaAlta extends Action {
    var $tpl = "tpl/guardia/tpl.GuardiaAlta.php";
    
    function inicializar() {

        // Titulo del template
        $this->asignar('accion_guardias', "Alta");

		// Cargo los combos
        $this->asignar('options_usuarios', ComboUsuario());        
    }

    function guardarGuardia()
    {        
        $guardia = new Guardia();        
        $guardia->setFields($_POST);
        $guardia->baja_logica = FALSE_;
        return $guardia->save();
    }

    function procesar(&$nextAction)
    {
        if ($this->guardarGuardia()){
            $nextAction->setNextAction('GuardiaAdm', 'alta.guardia.ok');
        }
        else{
            $nextAction->setNextAction('GuardiaAdm', 'alta.guardia.error',array(error => "1"));
        }
    }    
}
?>