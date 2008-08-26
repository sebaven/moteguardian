<?
/**
 * @date 20/05/2008
 * @version 1.0
 * @author glerendegui
 */

if(!defined("BASE_DIR_RELATIVO")) {
	die("ERR-CONF-Inclusión indebida de fichero global.php\n");
}

define("BATCH_LOG_ERROR",1);
define("BATCH_LOG_ALERT",2);
define("BATCH_LOG_DEBUG",3);

include_once(BASE_DIR."comun/Fecha.php");

function registrarLog($tipo, $comentarios) {
	global $archivoErrores,$nombreArchivoLog;

	$strTipo = "DESCONOCIDO";
	$fecha = new Fecha();
	$fecha->loadFromNow();
	
	switch($tipo) {
		case BATCH_LOG_ERROR: $strTipo = "ERROR";break;
		case BATCH_LOG_ALERT: $strTipo = "ALERT";break;
		case BATCH_LOG_DEBUG: $strTipo = "DEBUG";break;
	}	
	$ahora = getdate();
	$str = $fecha->toString()." - ".$strTipo." - ".$comentarios;
	fwrite($archivoErrores,$str."\r\n");
	fflush($archivoErrores);
	// Si es error lo mando por salida standard
	if(($tipo == BATCH_LOG_ERROR || $tipo == BATCH_LOG_ALERT)) {
		echo $str . " - Ver log ".$nombreArchivoLog."\r\n";
	}
}


// error handler function
function manejar_error($errno, $errstr, $errfile, $errline)
{
    switch ($errno) {
    case E_ERROR:
    	$str = "PHP-ERROR [$errno] $errstr - Abortando";
    	registrarLog(BATCH_LOG_ERROR,$str);
        break;
    case E_WARNING:
    	$str = "PHP-WARNING [$errno] $errstr ";
    	registrarLog(BATCH_LOG_ALERT,$str);
        break;
		
    case E_NOTICE:
    	$str = "PHP-NOTICE [$errno] $errstr ";
    	//registrarLog(BATCH_LOG_DEBUG,$str);
        break;

    default:
    	$str = "PHP-OTROS [$errno] $errstr ";
    	//registrarLog(BATCH_LOG_DEBUG,$str);
        break;
    }
	
    /* Don't execute PHP internal error handler */
    return true;
}

set_error_handler("manejar_error");

?>