<?

// Defino la carpeta raiz de la aplicacion
define("BASE_DIR_RELATIVO","../");
include_once(BASE_DIR_RELATIVO."batch/global.php");

include_once(FWK_DIR."data/AbstractDAO.php");
include_once(FWK_DIR."data/AbstractEntity.php");
include_once(BASE_DIR."clases/dao/dao.Configuracion.php");



if($argc != 2) {
	die("Use ".$argv[0]." ayuda\n");
}

function mostarAyuda() {
}

function iniciar() {
	global $_config;
	echo "Iniciando srdf... OK\n";
	exec($_config['path2Php'].' srdf.php > log/srdf.out &');	
}

/**
 * Setea el valor de la constante ESTADO en DETENIENDO y se queda esperando a que esta cambie a DETENIDO
 */
function detener() {
	global $configuracion;
	echo "Deteniendo srdf... ";
	$configuracion->valor = ESTADO_SRDFIP_DETENIENDO;
	$configuracion->save();	
	while(1) {
		sleep(2);
		$configuracion->load();
		if($configuracion->valor == ESTADO_SRDFIP_DETENIDO) break;
	}
	echo "OK\n";
}

function estado() {
	global $configuracion;
	switch($configuracion->valor) {
		case ESTADO_SRDFIP_DETENIDO: echo "srdf detenido\n";break;
		case ESTADO_SRDFIP_EJECUTANDO: echo "srdf ejecutando\n";break;
		case ESTADO_SRDFIP_DETENIENDO: echo "srdf deteniendo\n";break;
		case ESTADO_SRDFIP_LEVANTANDO: echo "srdf levantando\n";break;
	}
}



$configuracion = new Configuracion(CONST_CONFIG_ESTADO_SRDFIP);
switch($argv[1]) {
	case 'ayuda':	mostarAyuda();
					break;
	case 'iniciar': iniciar();
					break;
	case 'detener': detener();
					break;
	case 'estado':	estado();
					break;
	default:		die("Use ".$argv[0]." ayuda\n");
					break;
}

?>

