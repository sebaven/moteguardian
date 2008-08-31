<?
/**
 * @date 02/06/2008
 * @version 1.0
 * @author glerendegui
 */


// Defino la carpeta raiz de la aplicacion
define("BASE_DIR_RELATIVO","../");

include_once(BASE_DIR_RELATIVO."batch/global.php");
include_once(FWK_DIR."data/AbstractDAO.php");
include_once(FWK_DIR."data/AbstractEntity.php");
include_once(BASE_DIR."clases/dao/dao.Configuracion.php");
include_once(BASE_DIR."clases/dao/dao.Planificacion.php");
include_once(BASE_DIR."clases/batch/dao/dao.ProcesoEjecutandose.php");
include_once(BASE_DIR."clases/batch/entidades/clase.ProcesoEjecutandose.php");
include_once(BASE_DIR."clases/dao/dao.Configuracion.php");


$procesosLanzados = array();

function borrarTareasEjecutandose() {
	$procesoEjecutandoseDAO = new ProcesoEjecutandoseDAO();
	$procesoEjecutandoseDAO->borrarTareasEjecutandose();
}






$tiempoEspera = 14;

$configDAO = new ConfiguracionDAO();
$configuracion = $configDAO->getById(CONST_CONFIG_ESTADO_SRDFIP);
/*if($configuracion->valor != ESTADO_SRDFIP_DETENIDO) {
	die("El srdf ya esta corriendo\n");
}*/



// LEVANTANDO...
$configuracion->valor = ESTADO_SRDFIP_LEVANTANDO;
if(!$configuracion->save()) die("Imposible levantar el srdf\n");

borrarTareasEjecutandose();
echo "Comenzando ejecucion - Lanzar cada $tiempoEspera segundos\n";



// EJECUTANDO...
$configuracion->valor = ESTADO_SRDFIP_EJECUTANDO;
if(!$configuracion->save()) die("Imposible levantar el srdf\n");

while($configDAO->getById(CONST_CONFIG_ESTADO_SRDFIP)->valor != ESTADO_SRDFIP_DETENIENDO) {
	registrarLog(BATCH_LOG_ALERT,"Lanzando dispatcher...");
	$pid = pcntl_fork(); 
	if($pid == -1) {
		registrarLog(BATCH_LOG_ERROR,"Imposible crear proceso dispatcher");
		finalizarPorError();
	}
	else {
		if($pid == 0) {
			$comando = $_config["path2Php"];
			$args = array(BASE_DIR."batch/dispatcher.php");
			if(!pcntl_exec($comando,$args)) {
				registrarLog(BATCH_LOG_ERROR,"Imposible crear proceso dispatcher");
				finalizarPorError();
			}
		}
		else {
			$procesosLanzados[] = $pid;			
			registrarLog(BATCH_LOG_DEBUG,"Lanzado el dispatcher $pid");
		}
	}
	sleep($tiempoEspera);	
}

echo "Denetiendo, se queda a la espera de los hijos\n";

// Espero por los hijos
$stat = 0;
foreach($procesosLanzados as $proceso) {
	pcntl_waitpid($proceso,$stat);
}

$configuracion->valor = ESTADO_SRDFIP_DETENIDO;
$configuracion->save();
echo "Detenido exitosamente";


?>