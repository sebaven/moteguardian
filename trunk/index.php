<?
	error_reporting(E_ALL | E_STRICT);
	ob_start();
	
	if(get_magic_quotes_gpc()) {
		die(get_magic_quotes_gpc()."Error de configuración, quitar el magic_quotes");
	}
	
	include_once "ionix/config/ionix.php";
	include_once "comun/defines_app.php";
	include_once "comun/inc.global.php";
	include_once "comun/ComboHelper.php";
	include_once "comun/Fecha.php";
	
	include_once "clases/negocio/clase.Rol.php";
 	
 	session_start();
 	
	// Se setea el maximo tiempo de espera para que no emita error de time out
	ini_set("max_execution_time",MAX_EXECUTION_TIME);
	 	
 	// Se activa el logueo de errores en un archivo.
 	// Descomentar en produccion	
	//$error = new ErrorHandler('log/errors.log');

	// Inicializar registro
	if (! RegistryHelper::isInit())
		RegistryHelper::init();

	RegistryHelper::registerLanguage("es");

	// Tomar la accion que viene por GET
	$action = (! empty($_GET['accion'])) ? $_GET['accion'] : 'inicio';

 	
	// Buscar el path de la clase para la accion correspondiente o un path por defecto
	$actionFactory = new ActionFactory($action);
	$actionParams = $actionFactory -> create();
	$class_name = $actionParams['clase'];
	$modulo = $actionParams['modulo'];
	$action_name = $actionParams['nombre'];
	
	// Templates
	if (empty($_GET['pop']))
		include_once "comun/inc.template_arriba.php";
	else if ($_GET['pop'] == 1)
		include_once "comun/inc.template_pop_arriba.php";	
	

	// Si el usuario no esta logueado y no se esta solicitando la ejecucion de ningun proceso
	// se guarda la URL para aplicar el patron ReturnTo luego del logueo.
	if (!RegistryHelper::isUserLogged() && $action_name != 'Login')
	{
		//Codigo de ReturnTo
		if (isset($_GET['accion']) && $_GET['accion']!='logout')
			$params['returnAction'] = urlencode($_SERVER["QUERY_STRING"]);
		Application::Go("Login","",$params);
	}
	
	// Se valida que el usuario logueado tenga el permiso para ejecutar la accion solitada
	// segun su rol.
 	$rol = RegistryHelper::getRolUsuario();

	if ($action_name != 'Login' && !PermissionHelper::validateAccess($action_name, $rol->descripcion))
		Application::Go("PermisoDenegado");
	
	// Si el usuario esta logueado y se solicita LOGIN, redirigir a INICIO
	if (RegistryHelper::isUserLogged() && $action_name == 'Login') 
		Application::Go("Inicio","",$params);
		
	// Incluir el archivo con la clase
	include_once 'acciones/' . $modulo . '/accion.' . $class_name . '.php';

	// Crear la clase de la accion correspondiente
	eval('$actionController = new $class_name;');

	?>
		 <div class='aplicacion'>
			<?$actionController->ejecutarCiclo(); ?>	
		 </div>
	<?
	
	if (empty($_GET['pop']))
		include_once "comun/inc.template_pie.php";
	else if ($_GET['pop'] == 1)
		include_once "comun/inc.template_pop_pie.php";
		
?>