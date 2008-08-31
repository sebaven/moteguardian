<?
/**
 * @date 20/05/2008
 * @version 1.0
 * @author glerendegui
 */


if(!defined("BASE_DIR_RELATIVO")) {
	die("ERR-CONF-Inclusión indebida de fichero global.php\n");
}

// Configuracion del sistema SRDF
$_config['ficheroLog'] = BASE_DIR."batch/log/"; 
$_config['path2Php'] = "/usr/bin/php";
$_config['pathBaseArchivosTemporales'] = BASE_DIR."batch/tmp/";


?>