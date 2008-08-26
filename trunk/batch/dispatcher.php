<?
/**
 * @date 20/05/2008
 * @version 1.0
 * @author glerendegui
 */


// Defino la carpeta raiz de la aplicacion
define("BASE_DIR_RELATIVO","../");

/*
 * TODO:  Borro logs por debug--->sacar!!!
 */
function borrarLogs() {
	$dh = opendir(BASE_DIR_RELATIVO."batch/log");
 	while (($file = readdir($dh)) !== false) {
 			if(filetype(BASE_DIR_RELATIVO."batch/log/".$file)=="file")
 				unlink(BASE_DIR_RELATIVO."batch/log/".$file);
        }
    closedir($dh);	
}
//borrarLogs();

include_once(BASE_DIR_RELATIVO."batch/global.php");

include_once(FWK_DIR."data/AbstractDAO.php");
include_once(FWK_DIR."data/AbstractEntity.php");
include_once(BASE_DIR."clases/dao/dao.Configuracion.php");
include_once(BASE_DIR."clases/dao/dao.Actividad.php");
include_once(BASE_DIR."clases/dao/dao.ActividadEnvio.php");
include_once(BASE_DIR."clases/batch/dao/dao.ProcesoEjecutandose.php");
include_once(BASE_DIR."clases/batch/entidades/clase.ProcesoEjecutandose.php");


$procesosLanzados = array();

/*
 * buscarFechaUltimaCorridaDispatcher
 * returns Fecha
 */
function buscarFechaUltimaCorridaDispatcher() {
	registrarLog(BATCH_LOG_DEBUG,"Buscando fecha ultima vez que fue lanzado");
	$configDAO = new ConfiguracionDAO();
	$configuracion = $configDAO->getById(CONST_CONFIG_ULTIMA_EJECUCION_DISPATCHER);
	$fecha = new Fecha();
	if(!$fecha->loadFromString($configuracion->valor)) 	{
		registrarLog(BATCH_LOG_ERROR,"Fecha de última ejecución del dispatcher en la base mal formada");
		finalizarPorError();
	}
	return $fecha;
}

/*
 * setearFechaUltimaCorridaDispatcher
 * returns bool - Grabó ok.
 */
 function setearFechaUltimaCorridaDispatch($fecha) {
	registrarLog(BATCH_LOG_DEBUG,"Seteando la última echa en que fue lanzado en $fecha");
	
	$configDAO = new ConfiguracionDAO();
	$configuracion = $configDAO->getById(CONST_CONFIG_ULTIMA_EJECUCION_DISPATCHER);
	$configuracion->valor = $fecha;
	if (!$configuracion->save()) {
		registrarLog(BATCH_LOG_ERROR,"Fecha de última ejecución del dispatcher en la base imposible de grabar");
		finalizarPorError();
		return false;
	}
	return true;
 	
 }

/* 
 * buscarActividadesALanzar
 * Función que buscar las planiicaciones que deben lanzarse ahora
 * Devuelve un array con las actividades.
 */
function buscarActividadesALanzar($ultimoLanzamiento,$ahora) {
	$procesoEjecutandoseDAO = new ProcesoEjecutandoseDAO();
	
	$actividadDAO = new ActividadDAO();
	$actividades = $actividadDAO->filterByFecha($ultimoLanzamiento,$ahora);

	foreach($actividades as $key=>$actividad) {
			$activ = $procesoEjecutandoseDAO->getByIdActividad($actividad->id,TIPO_PROCESO_RECOLECCION);
			if(!empty($activ)) {
				// Ya se está ejecutando
				registrarLog(BATCH_LOG_ALERT,"Recolección ya ejecutandose - Actividad ".$actividad->id);
				unset($actividades[$key]);	
			}				
			else {
				// La marco como ejecutandose
				$procesoEjecutandose = new ProcesoEjecutandose();
				$procesoEjecutandose->id_actividad = $actividad->id;
				$procesoEjecutandose->tipo = TIPO_PROCESO_RECOLECCION;
				$procesoEjecutandose->save();
			}
	}
	return $actividades;

}

/* 
 * buscarEnviosALanzar
 * Función que buscar las planiicaciones que deben lanzarse ahora
 * Devuelve un array con las actividadesEnvios.
 */
function buscarEnviosALanzar($ultimoLanzamiento,$ahora) {
	
	$procesoEjecutandoseDAO = new ProcesoEjecutandoseDAO();

	$actividadEnvioDAO = new ActividadEnvioDAO();
	$actividadesEnvios = $actividadEnvioDAO->filterByFecha($ultimoLanzamiento,$ahora);
	
	foreach($actividadesEnvios as $key=>$actividad) {
			$activ = $procesoEjecutandoseDAO->getByIdActividad($actividad->id,TIPO_PROCESO_ENVIO);
			if(!empty($activ)) {
				// Ya se está ejecutando
				registrarLog(BATCH_LOG_ALERT,"Envio ya ejecutandose - Actividad Envio".$actividad->id);
				unset($actividadesEnvios[$key]);	
			}				
			else {
				// La marco como ejecutandose
			/*	$procesoEjecutandose = new ProcesoEjecutandose();
				$procesoEjecutandose->id_actividad = $actividad->id;
				$procesoEjecutandose->tipo = TIPO_PROCESO_ENVIO;
				$procesoEjecutandose->save();*/
			}
	}
	return $actividadesEnvios;

}


function lanzarRecoleccion($idActividad) {
	return lanzarProceso(array(BASE_DIR."batch/nucleo_srdf.php","recolectar",$idActividad));
}

function lanzarEnvio($idActividadEnvio) {
	return lanzarProceso(array(BASE_DIR."batch/nucleo_srdf.php","enviar",$idActividadEnvio));
}

function lanzarProceso($args) {
	global $_config,$procesosLanzados;
	// FIXME: Verificar si hay procesos hijos antes de terminar
	registrarLog(BATCH_LOG_ALERT,"Lanzando Nucleo para actividad ".implode(":",$args));
	$pid = pcntl_fork(); 
	if($pid == -1) {
		registrarLog(BATCH_LOG_ERROR,"Imposible crear procesos nucleo");
	}
	else {
		if($pid == 0) {
			$comando = $_config["path2Php"];
			if(!pcntl_exec($comando,$args)) {
				registrarLog(BATCH_LOG_ERROR,"Imposible crear procesos nucleo");
			}
		}
		else {
			$procesosLanzados[] = $pid;			
			registrarLog(BATCH_LOG_DEBUG,"Lanzado el nucleo $pid - ".implode(":",$args));
		}
	}
}

/*
 * main
 * Función de ejecución principal del programa
 */
function main() {
	global $procesosLanzados;
	registrarLog(BATCH_LOG_ALERT,"Comenzando ejecucion de dispatcher");
	registrarLog(BATCH_LOG_DEBUG,"Comenzando ejecución de dispatcher");
	registrarLog(BATCH_LOG_DEBUG,"Trabajando en ".getcwd());
	
	$ultimoLanzamiento = buscarFechaUltimaCorridaDispatcher();
	$ahora = new Fecha();
	$ahora->loadFromNow();
	setearFechaUltimaCorridaDispatch($ahora->toString());
	 
	
	$actividades = buscarActividadesALanzar($ultimoLanzamiento,$ahora);
	foreach($actividades as $actividad) {
			lanzarRecoleccion($actividad->id);
	}
	
	$actividadesEnvios = buscarEnviosALanzar($ultimoLanzamiento,$ahora);
	foreach($actividadesEnvios as $actividadEnvio) {
		lanzarEnvio($actividadEnvio->id);
	}
	
	// Espero por los hijos
	$stat = 0;
	foreach($procesosLanzados as $proceso) {
		pcntl_waitpid($proceso,$stat);
	}
	registrarLog(BATCH_LOG_ALERT,"Terminada ejecucion de dispatcher");
	registrarLog(BATCH_LOG_DEBUG,"Terminada ejecución de dispatcher");
}



// Lanzo el main
main();






?>