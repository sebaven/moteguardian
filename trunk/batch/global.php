<?
/**
 * @date 20/05/2008
 * @version 1.0
 * @author glerendegui
 */


if(!defined("BASE_DIR_RELATIVO")) {
	die("ERR-CONF-Inclusión indebida de fichero global.php\n");
}

define("BASE_DIR",realpath(BASE_DIR_RELATIVO)."/");

include_once(BASE_DIR."comun/defines_app.php");
include_once(BASE_DIR."ionix/config/ionix-config.php");
include_once(BASE_DIR."ionix/data/Db.php"); 
include_once(BASE_DIR."ionix/data/DbMySql.php"); 
include_once(BASE_DIR."ionix/data/ConnectionManager.php");
include_once(BASE_DIR."comun/Fecha.php");
include_once(BASE_DIR."clases/util/clase.FechaHelper.php");

include_once(BASE_DIR."batch/config.php");
include_once(BASE_DIR."batch/errores.php");
include_once(BASE_DIR."batch/funciones.php");

// Incluyo modulos

$nombre = pathinfo($argv[0]);
$nombreArchivoLog = $_config['ficheroLog'].$nombre['filename']."_".posix_getpid().".log";
$db = ConnectionManager::getConnection();
$archivoErrores = fopen($nombreArchivoLog,"a");
if(!$archivoErrores) die("Imposible abrir archivo de logs: ".$_config['ficheroLog']);

define("TIPO_PROCESO_RECOLECCION",1);
define("TIPO_PROCESO_ENVIO",2);
?>