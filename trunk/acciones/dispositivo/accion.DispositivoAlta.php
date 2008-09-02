<?
include_once BASE_DIR ."clases/negocio/clase.Dispositivo.php";
include_once BASE_DIR ."clases/listados/clase.Listados.php";
//include_once BASE_DIR ."validadores/UsuarioExisteValidator.php";
include_once BASE_DIR ."validadores/CaracteresInvalidos.php";
include_once BASE_DIR ."clases/service/clase.Logger.php";
include_once BASE_DIR ."comun/defines_acciones_logger.php";
//include_once BASE_DIR ."validadores/Emailvalidator.php";
include_once BASE_DIR ."validadores/ShowError.php";

class DispositivoAlta extends Action {
    var $tpl = "tpl/dispositivo/tpl.DispositivoAlta.php";
    
    function inicializar() {

        // Titulo del template
        $this->asignar('accion_dispositivos', "Alta");
        
        // Tipos de Dispositivo
        $this->asignar('options_tipo', ComboTipoDispositivo());
        $this->asignar('options_sala', ComboSala());
    }

    /*function validar(&$v)
    {
        $v->add(new Required('usuario', 'usuario.required'), false);
        $v->add(new Required('clave', 'password.required'), false);        
        $v->add(new Required('id_rol', 'rol.required'), false);
        
        // Se valida que el usuario que se esta creando no contenga caracteres invalidos
        // Solo podra tener caracteres a-z, A-Z y 0-9
        $v->add(new CaracteresInvalidos('usuario', 'usuario.caracteres.invalidos'), false);

        $v->add(new UsuarioExisteValidator($_POST['usuario'], 'alta.usuario.existente'), false);
        
        // Se valida que la nueva clave tenga mas de 5 caracteres
        $v->add(new Condition(strlen($_POST['clave']) >= 8,'cambiarPassword.passConfirm.length'));
        $v->add(new Condition(strlen($_POST['clave']) <= 255,'cambiarPassword.passConfirm.length'));
        
        //Si el usuario completo el campo "mail" este se verifica
        if ($_POST['email'])
        {
            // Se verifica que el mail sea v�lido.
            // La funcion "trim" saca los espacios vacios de una cadena.
            $v->add(new EmailValidator( trim( $_POST['email'] ), 'usuario.email.invalido'), false);
        }
        
        //Si el usuario completo el campo "otro mail" este se verifica 
        if ($_POST['otros_mails'])
        {
            // se fija si hay mas de un mail
            if (strpos($_POST['otros_mails'] , ';')==false)
            {  
                // Si hay un solo mail se verifica que sea v�lido
                $v->add(new EmailValidator( trim($_POST['otros_mails']), 'usuario.otros_mails.invalido'), false);
        
            }else{
                //se obtienen todos los mails
                $mails = explode(";", $_POST['otros_mails']);
                
                for ($i = 0; $i < count($mails) ; $i++) {
                     // Se verifica que cada mail sea v�lido
                     $v->add(new EmailValidator( trim($mails[$i]), 'usuario.otros_mails.invalido'), false);
                }
            }
        }
        
        if ((strstr($_POST['email'], ";"))){
            
            $v->add(new ShowError('caracteres.incorrectos'));
        } 
    }*/
        
    
    /*function defaults()
    {
        parent::defaults();
        
        // Se oculta el combo de clubes
        $this->asignar('mostrarComboClubes', "none");
    } */

    function guardarDispositivo(){
        
        $dispositivo = new Dispositivo();
        
        $dispositivo->tipo = $_POST['id_tipo'];
        $dispositivo->id_sala = $_POST['id_sala'];
        $dispositivo->codigo = $_POST['codigo'];
        $dispositivo->estado = $_POST['estado'];
        $dispositivo->descripcion = $_POST['descripcion'];
        $dispositivo->baja_logica = 0;
        // Indica si el trabajo se ha guardado correctamente o no.
        return $dispositivo->save();
    }

    function procesar(&$nextAction)
    {
        if ($this->guardarDispositivo()){
            $nextAction->setNextAction('DispositivoAlta', 'alta.usuario.ok');
        }
        else{
            $nextAction->setNextAction('DispositivoAlta', 'alta.usuario.error',array(error => "1"));
        }
    }    
}
?>