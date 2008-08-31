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
include_once(BASE_DIR."clases/dao/dao.Recoleccion.php");
include_once(BASE_DIR."clases/dao/dao.Envio.php");
include_once(BASE_DIR."clases/batch/dao/dao.ProcesoEjecutandose.php");
include_once(BASE_DIR."clases/batch/entidades/clase.ProcesoEjecutandose.php");


$procesosLanzados = array();

/*
 * buscarFechaUltimaCorridaDispatcher
 * returns Fecha
 */
function buscarFechaUltimaCorridaDispatcher() {
	registrarLog(BATCH_LOG_DEBUG,"Buscando fecha última vez que fué lanzado");
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
	registrarLog(BATCH_LOG_DEBUG,"Seteando la última fecha en que fué lanzado en $fecha");
	
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
 * buscarRecoleccionesALanzar
 * Funcion que buscar las recolecciones que deben lanzarse ahora
 * Devuelve un array con las recolecciones.
 */
function buscarRecoleccionesALanzar($ultimoLanzamiento,$ahora) {
	$procesoEjecutandoseDAO = new ProcesoEjecutandoseDAO();
	
	$recoleccionDAO = new RecoleccionDAO();
	$recolecciones = $recoleccionDAO->filterByFecha($ultimoLanzamiento,$ahora);

	foreach($recolecciones as $key => $recoleccion) {
		if($recoleccion->habilitado) {
			$recol = $procesoEjecutandoseDAO->getByIdRecoleccion($recoleccion->id,TIPO_PROCESO_RECOLECCION);
			if(!empty($recol)) {
				// Ya se esta ejecutando
				registrarLog(BATCH_LOG_ALERT,"Recolección ya ejecutandose - Recolección ".$recoleccion->id);
				unset($recolecciones[$key]);	
			}				
			else {
				// La marco como ejecutandose
				$procesoEjecutandose = new ProcesoEjecutandose();
				$procesoEjecutandose->id_recoleccion = $recoleccion->id;
				$procesoEjecutandose->tipo = TIPO_PROCESO_RECOLECCION;
				$procesoEjecutandose->save();
			}
		}
	}
	return $recolecciones;

}

/* 
 * buscarEnviosALanzar
 * Funcion que buscar los envios que deben lanzarse ahora
 * Devuelve un array con los envios.
 */
function buscarEnviosALanzar($ultimoLanzamiento,$ahora) {
	
	$procesoEjecutandoseDAO = new ProcesoEjecutandoseDAO();

	$envioDAO = new EnvioDAO();
	$envios = $envioDAO->filterByFecha($ultimoLanzamiento,$ahora);
	
	foreach($envios as $key => $envio) {
		if ($envio->habilitado) {
			$env = $procesoEjecutandoseDAO->getByIdRecoleccion($envio->id_recoleccion,TIPO_PROCESO_ENVIO);
			if(!empty($env)) {
				// Ya se esta ejecutando
				registrarLog(BATCH_LOG_ALERT,"Envío ya ejecutandose - Envío".$envio->id);
				unset($envios[$key]);	
			}				
			else {
				// TODO: Esto siempre lo vi comentado...verificar si está bien o no!
				// La marco como ejecutandose
			/*	$procesoEjecutandose = new ProcesoEjecutandose();
				$procesoEjecutandose->id_recoleccion = $envio->id_recoleccion;
				$procesoEjecutandose->tipo = TIPO_PROCESO_ENVIO;
				$procesoEjecutandose->save();*/
			}
		}
	}
	return $envios;

}


function lanzarRecoleccion($idRecoleccion) {
	return lanzarProceso(array(BASE_DIR."batch/nucleo_srdf.php","recolectar",$idRecoleccion));
}

function lanzarEnvio($idEnvio) {
	return lanzarProceso(array(BASE_DIR."batch/nucleo_srdf.php","enviar",$idEnvio));
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
 * Funcion de ejecucion principal del programa
 */
function main() {
	global $procesosLanzados;
	registrarLog(BATCH_LOG_ALERT,"Comenzando ejecución de dispatcher");
	registrarLog(BATCH_LOG_DEBUG,"Comenzando ejecución de dispatcher");
	registrarLog(BATCH_LOG_DEBUG,"Trabajando en ".getcwd());
	
	$ultimoLanzamiento = buscarFechaUltimaCorridaDispatcher();
	$ahora = new Fecha();
	$ahora->loadFromNow();
	setearFechaUltimaCorridaDispatch($ahora->toString());
	 
	
	$recolecciones = buscarRecoleccionesALanzar($ultimoLanzamiento,$ahora);
	foreach($recolecciones as $recoleccion) {
		if ($recoleccion->habilitado) lanzarRecoleccion($recoleccion->id);
	}
	
	$envios = buscarEnviosALanzar($ultimoLanzamiento,$ahora);
	foreach($envios as $envio) {
		if ($envio->habilitado) lanzarEnvio($envio->id);
	}
	
	// Espero por los hijos
	$stat = 0;
	foreach($procesosLanzados as $proceso) {
		pcntl_waitpid($proceso,$stat);
	}
	registrarLog(BATCH_LOG_ALERT,"Terminada ejecución de dispatcher");
	registrarLog(BATCH_LOG_DEBUG,"Terminada ejecución de dispatcher");
}



// Lanzo el main
main();






?>