<?php
include_once BASE_DIR ."clases/negocio/clase.Configuracion.php";
include_once BASE_DIR ."clases/dao/dao.Configuracion.php";
include_once BASE_DIR ."clases/service/clase.Logger.php";
include_once BASE_DIR ."comun/defines_acciones_logger.php";
include_once BASE_DIR . 'validadores/MinMaxValueValidator.php';
include_once BASE_DIR . 'validadores/IPValidator.php';
include_once BASE_DIR . 'validadores/NumericInteger.php';

class ConfiguracionSistema extends Action {
	var $tpl = "tpl/general/tpl.ConfiguracionSistema.php";

	
	function inicializar() {				
		// Carga de los datos de configuraciï¿½n
		$confDiasDisco = new Configuracion(CONST_CONFIG_ALMACENAMIENTO_DISCO);
		$confDiasBD = new Configuracion(CONST_CONFIG_ALMACENAMIENTO_BD);
		$ubicacionExito = new Configuracion(CONST_CONFIG_UBICACION_FICHEROS_EXITO);
		$ubicacionError = new Configuracion(CONST_CONFIG_UBICACION_FICHEROS_ERROR);
		$ubicacionBase = new Configuracion(CONST_CONFIG_UBICACION_FICHEROS_BASE);
		$ubicacionEnviados = new Configuracion(CONST_CONFIG_UBICACION_FICHEROS_ENVIADOS);
		$estadoSRDFIP = new Configuracion(CONST_CONFIG_ESTADO_SRDFIP);
		$direccionIP = new Configuracion(CONST_CONFIG_FTP_LOCAL_IP);
		$puerto = new Configuracion(CONST_CONFIG_FTP_LOCAL_PUERTO);
		$ubicacionOrigen = new Configuracion(CONST_CONFIG_FTP_LOCAL_UBICACION_RECOLECCION);
		$ubicacionDestino = new Configuracion(CONST_CONFIG_FTP_LOCAL_UBICACION_ENVIO);
		$intentosConexion = new Configuracion(CONST_CONFIG_FTP_LOCAL_INTENTOS);
		$timeout = new Configuracion(CONST_CONFIG_FTP_LOCAL_TIMEOUT);
		$nombreUsuario = new Configuracion(CONST_CONFIG_FTP_LOCAL_USUARIO);
		$pass = new Configuracion(CONST_CONFIG_FTP_LOCAL_PASSWORD);

		$this->asignar('dias_disco',$confDiasDisco->valor);
		$this->asignar('dias_bd',$confDiasBD->valor);
		$this->asignar('ubicacion_ficheros_exito',$ubicacionExito->valor);
		$this->asignar('ubicacion_ficheros_error',$ubicacionError->valor);
		$this->asignar('ubicacion_ficheros_base',$ubicacionBase->valor);
		$this->asignar('ubicacion_ficheros_enviados',$ubicacionEnviados->valor);
		$this->asignar('direccion_ip',$direccionIP->valor);
		$this->asignar('puerto',$puerto->valor);
		$this->asignar('ubicacion_recoleccion',$ubicacionOrigen->valor);
		$this->asignar('ubicacion_envio',$ubicacionDestino->valor);
		$this->asignar('intentos',$intentosConexion->valor);
		$this->asignar('timeout',$timeout->valor);
		$this->asignar('usuario',$nombreUsuario->valor);
		$this->asignar('contrasenia',$pass->valor);
		
		$this->actualizarBotonIniciando();		
	}


	function validar(&$v){		
		$v->add(new Required('dias_disco', 'configuracion.dias_disco'));
		$v->add(new Required('dias_bd', 'configuracion.dias_bd'));	
		$v->add(new Required('ubicacion_ficheros_exito', 'configuracion.ubicacion_ficheros_exito'));	
		$v->add(new Required('ubicacion_ficheros_error', 'configuracion.ubicacion_ficheros_error'));
		$v->add(new Required('ubicacion_ficheros_base', 'configuracion.ubicacion_ficheros_base'));
		$v->add(new Required('ubicacion_ficheros_enviados', 'configuracion.ubicacion_ficheros_enviados'));
		
		$v->add(new Required('direccion_ip', 'configuracion.ftp.direccionip.requerido'));
		$v->add(new Required('puerto', 'configuracion.ftp.puerto.requerido'));
		$v->add(new Required('ubicacion_recoleccion', 'configuracion.ftp.ubicacion.recoleccion.requerido'));
		$v->add(new Required('ubicacion_envio', 'configuracion.ftp.ubicacion.envio.requerido'));
		$v->add(new Required('intentos', 'configuracion.ftp.intentos.requerido'));
		$v->add(new Required('timeout', 'configuracion.ftp.timeout.requerido'));
		$v->add(new Required('contrasenia', 'configuracion.ftp.usuario.requerido'));
		$v->add(new Required('contrasenia', 'configuracion.ftp.contrasenia.requerido'));
		
		$v->add(new NumericInteger('dias_disco','configuracion.dias_disco.numerico'),true);
		$v->add(new NumericInteger('dias_bd','configuracion.dias_bd.numerico'),true);
		
		$v->add(new MinMaxValueValidator('puerto', 1, 65535, 'origenFTP.puerto.rango'), true);
		$v->add(new NumericInteger('puerto','origenFTP.puerto.numerico'),true);
		$v->add(new MinMaxValueValidator('intentos', 1, 100, 'origenFTP.nroIntentos.rango'), true);
		$v->add(new NumericInteger('intentos','origenFTP.nroIntentos.numerico'),true);
		$v->add(new IPValidator($_POST['direccion_ip'],'origenFTP.direccionIP.formato'), true);
		$v->add(new MinMaxValueValidator('timeout', 1, 5000, 'timeout.nroIntentos.rango'), true);			

		if($_POST['dias_disco']<0) $v->add(new Condition($_POST['dias_disco']>0,'configuracion.dias_disco.numerico.positivo' ),true);
		if($_POST['dias_bd']<0) $v->add(new Condition($_POST['dias_bd']>0,'configuracion.dias_bd.numerico.positivo' ),true);
	}

	
	function cambiarEstadoIniciado() {		
		$estadoSRDFIP = new Configuracion(CONST_CONFIG_ESTADO_SRDFIP);
		
		if($estadoSRDFIP->valor==ESTADO_SRDFIP_EJECUTANDO){
			// Se detiene la aplicación			
			$estadoSRDFIP->valor = ESTADO_SRDFIP_DETENIENDO;
			$estadoSRDFIP->update();
		} else if($estadoSRDFIP->valor==ESTADO_SRDFIP_DETENIDO){
			// Se inicia la aplicación												
			shell_exec('chdir '.BASE_DIR.'batch/; nohup ./srdf_init.sh > log/srdf.out  2 > log/srdf.out &');						
		}
		
		sleep(2);
		printr($a);
		$this->actualizarBotonIniciando();
	}

	
	function procesar(&$nextAction)	{
		$confDiasDisco = new Configuracion(CONST_CONFIG_ALMACENAMIENTO_DISCO);
		$confDiasBD = new Configuracion(CONST_CONFIG_ALMACENAMIENTO_BD);
		$ubicacionExito = new Configuracion(CONST_CONFIG_UBICACION_FICHEROS_EXITO);
		$ubicacionError = new Configuracion(CONST_CONFIG_UBICACION_FICHEROS_ERROR);
		$ubicacionBase = new Configuracion(CONST_CONFIG_UBICACION_FICHEROS_BASE);
		$ubicacionEnviados = new Configuracion(CONST_CONFIG_UBICACION_FICHEROS_ENVIADOS);
		$direccionIP = new Configuracion(CONST_CONFIG_FTP_LOCAL_IP);
		$puerto = new Configuracion(CONST_CONFIG_FTP_LOCAL_PUERTO);
		$ubicacionOrigen = new Configuracion(CONST_CONFIG_FTP_LOCAL_UBICACION_RECOLECCION);
		$ubicacionDestino = new Configuracion(CONST_CONFIG_FTP_LOCAL_UBICACION_ENVIO);
		$intentosConexion = new Configuracion(CONST_CONFIG_FTP_LOCAL_INTENTOS);
		$timeout = new Configuracion(CONST_CONFIG_FTP_LOCAL_TIMEOUT);
		$nombreUsuario = new Configuracion(CONST_CONFIG_FTP_LOCAL_USUARIO);
		$pass = new Configuracion(CONST_CONFIG_FTP_LOCAL_PASSWORD);
		
		$confDiasDisco->valor = $_POST['dias_disco'];
		$confDiasBD->valor = $_POST['dias_bd'];
		$ubicacionExito->valor = $_POST['ubicacion_ficheros_exito'];
		$ubicacionError->valor = $_POST['ubicacion_ficheros_error'];
		$ubicacionBase->valor = $_POST['ubicacion_ficheros_base'];
		$ubicacionEnviados->valor = $_POST['ubicacion_ficheros_enviados'];

		$direccionIP->valor = $_POST['direccion_ip'];
		$puerto->valor = $_POST['puerto'];
		$ubicacionOrigen->valor = $_POST['ubicacion_recoleccion'];
		$ubicacionDestino->valor = $_POST['ubicacion_envio'];
		$intentosConexion->valor = $_POST['intentos'];
		$timeout->valor = $_POST['timeout'];
		$nombreUsuario->valor = $_POST['usuario'];
		$pass->valor = $_POST['contrasenia'];
		
		if ($confDiasDisco->update()&&$confDiasBD->update()&&$ubicacionBase->update()&&$ubicacionError->update()&&$ubicacionExito->update()&&$ubicacionEnviados->update()&&$direccionIP->update()&&$puerto->update()&&$ubicacionOrigen->update()&&$ubicacionDestino->update()&&$intentosConexion->update()&&$timeout->update()&&$nombreUsuario->update()&&$pass->update()){
			$nextAction->setNextAction("Inicio", "modificacion.configuracion.ok");
			$usuario = new Usuario(RegistryHelper::getIdUsuario());
		}
		else{
			$nextAction->setNextAction("Inicio", "modificacion.configuracion.error", array(error => "1"));
		}
	}
	
	
	function actualizarBotonIniciando() {
		$estadoSRDFIP = new Configuracion(CONST_CONFIG_ESTADO_SRDFIP);
		
		if($estadoSRDFIP->valor == ESTADO_SRDFIP_LEVANTANDO ) {
			$this->asignar("boton_iniciado", "Iniciando, Espere...");
			$this->asignar("boton_iniciado_disabled", "disabled");
		}
		else if($estadoSRDFIP->valor == ESTADO_SRDFIP_EJECUTANDO){
			$this->asignar("boton_iniciado", "Detener");		
			$this->asignar("boton_iniciado_disabled", "");	
		}
		else if($estadoSRDFIP->valor == ESTADO_SRDFIP_DETENIENDO){
			$this->asignar("boton_iniciado", "Deteniendo, Espere... ");
			$this->asignar("boton_iniciado_disabled", "disabled");			
		}
		else if($estadoSRDFIP->valor == ESTADO_SRDFIP_DETENIDO){
			$this->asignar("boton_iniciado", "Iniciar");
			$this->asignar("boton_iniciado_disabled", "m");				
		}
	}
}
?>