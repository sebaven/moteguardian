<?php
include_once BASE_DIR ."clases/negocio/clase.Configuracion.php";
include_once BASE_DIR ."clases/dao/dao.Configuracion.php";
include_once BASE_DIR ."clases/service/clase.Logger.php";
include_once BASE_DIR ."comun/defines_acciones_logger.php";


class ConfiguracionSistema extends Action {
	var $tpl = "tpl/general/tpl.ConfiguracionSistema.php";

	function inicializar() 
	{				
		// Carga de los datos de configuración
		$confDiasDisco = new Configuracion(CONST_CONFIG_ALMACENAMIENTO_DISCO);
		$confDiasBD = new Configuracion(CONST_CONFIG_ALMACENAMIENTO_BD);

		$this->asignar('dias_disco',$confDiasDisco->valor);
		$this->asignar('dias_bd',$confDiasBD->valor);
	}


	function validar(&$v) 
	{		
		$v->add(new Required('dias_disco', 'configuracion.dias_disco'));
		$v->add(new Required('dias_bd', 'configuracion.dias_bd'));	
	}


	function procesar(&$nextAction)
	{
		$confDiasDisco = new Configuracion(CONST_CONFIG_ALMACENAMIENTO_DISCO);
		$confDiasBD = new Configuracion(CONST_CONFIG_ALMACENAMIENTO_BD);
		
		$confDiasDisco->valor = $_POST['dias_disco'];
		$confDiasBD->valor = $_POST['dias_bd'];

		if ($confDiasDisco->update()&&$confDiasBD->update()){
			$nextAction->setNextAction("Inicio", "modificacion.configuracion.ok");
			$usuario = new Usuario(RegistryHelper::getIdUsuario());
			Logger::register(CAMBIO_CONFIGURACION_SISTEMA, 'El usuario '.$usuario->nombre.' establecio '.$confDiasBD->nombre.'='.$confDiasBD->valor.', '.$confDiasDisco->nombre.'='.$confDiasDisco->valor);
		}
		else{
			$nextAction->setNextAction("Inicio", "modificacion.configuracion.error");
		}
	}
}
?>