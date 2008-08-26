<?php
/**
 * @date 20/05/2008
 * @version 1.0
 * @author glerendegui
 */


// Defino la carpeta raiz de la aplicacion
define("BASE_DIR_RELATIVO","../");
include_once(BASE_DIR_RELATIVO."batch/global.php");

include_once(FWK_DIR."data/AbstractDAO.php");
include_once(FWK_DIR."data/AbstractEntity.php");
include_once(BASE_DIR."clases/dao/dao.Actividad.php");
include_once(BASE_DIR."clases/dao/dao.TecnologiaRecolector.php");
include_once(BASE_DIR."clases/batch/dao/dao.ProcesoEjecutandose.php");
include_once(BASE_DIR."clases/batch/entidades/clase.ProcesoEjecutandose.php");
include_once(BASE_DIR."clases/negocio/clase.ActividadEjecutada.php");
include_once(BASE_DIR."clases/dao/dao.Plantilla.php");
include_once(BASE_DIR."clases/dao/dao.NodoPlantilla.php");
include_once(BASE_DIR."clases/negocio/clase.Plantilla.php");
include_once(BASE_DIR."clases/negocio/clase.TipoFiltro.php");
include_once(BASE_DIR."clases/dao/dao.ActividadEnvio.php");
include_once(BASE_DIR."clases/dao/dao.Tarea.php");
include_once(BASE_DIR."clases/negocio/clase.TecnologiaEnviador.php");

include_once(BASE_DIR."clases/modulos/instalados/clase.ModuloRecolectorFTP.php");
include_once(BASE_DIR."clases/modulos/instalados/clase.ModuloEnviadorFTP.php");
include_once(BASE_DIR."clases/modulos/instalados/clase.ModuloFiltroValidador.php");


function guardarFinalizacionProcesamiento($actividad,$tipo) {
	$procesoEjecutandoseDAO = new ProcesoEjecutandoseDAO();
	$procesoEjecutandose = $procesoEjecutandoseDAO->getByIdActividad($actividad->id,$tipo);
	if(empty($procesoEjecutandose)) {
		return false;
	}
	$procesoEjecutandose->delete();
	return true;	
}
 
function cargarRecolector($actividad) {
	global $modulos,$_config;
	// Busco el handler de recoleccion
	$tecnologiaRecolectorDAO = new TecnologiaRecolectorDAO();
	$tecnologiaRecolector = $tecnologiaRecolectorDAO->getByIdCentral($actividad->id_central);
	
	$archivoHandler = BASE_DIR."clases/modulos/instalados/clase.".$tecnologiaRecolector->clase_handler.".php";
	// Valido que exista el archivo del modulo
	if(!file_exists($archivoHandler)) {
		registrarLog(BATCH_LOG_ERROR,"Modulo ".$tecnologiaRecolector->clase_handler." no encontrado en clases/modulos/instalados");
		guardarFinalizacionProcesamiento($actividad,TIPO_PROCESO_RECOLECCION);
		finalizarPorError();
	}
	include_once($archivoHandler);
	
	// Valido que exista la clase
	if(!class_exists($tecnologiaRecolector->clase_handler)) {
		registrarLog(BATCH_LOG_ERROR,"Clase no definida ".$tecnologiaRecolector->clase_handler);
		guardarFinalizacionProcesamiento($actividad,TIPO_PROCESO_RECOLECCION);
		finalizarPorError();
	}
	
	$nombreClase = $tecnologiaRecolector->clase_handler;
	$recolector = new $nombreClase();
	
	// Valido que implemente Recolector
	if(!is_subclass_of($recolector,"Recolector")) {
		registrarLog(BATCH_LOG_ERROR,"Clase definida ".$tecnologiaRecolector->clase_handler." no implementa Recolector");
		guardarFinalizacionProcesamiento($actividad,TIPO_PROCESO_RECOLECCION);
		finalizarPorError();
	}

	if(!($recolector->_setCarpetaDestino($_config['pathBaseArchivosTemporales'].$nombreClase))) {
		guardarFinalizacionProcesamiento($actividad,TIPO_PROCESO_RECOLECCION);
		finalizarPorError();
	}
	$recolector->preparar($actividad);
	$modulos['recolector'] = $recolector;
	return true;	
}

function cargarFiltros($actividad) {
	global $modulos,$_config;
	$modulos['filtros'] = array();

	$nodosPlantilla = array();
	
	$plantilla = new Plantilla();
	$plantilla->id = $actividad->id_plantilla;
	$plantilla->load();
	
	
	if(empty($plantilla->nombre)) {
		registrarLog(BATCH_LOG_ERROR,"Plantilla de la actividad ".$actividad->nombre." incorrecto");
		guardarFinalizacionProcesamiento($actividad,TIPO_PROCESO_RECOLECCION);
		finalizarPorError();
	}
	
	// Busco el primero
	$nodoPlantillaDAO = new NodoPlantillaDAO();
	$nodoPlantilla = $nodoPlantillaDAO->getPrimeroEnPlantilla($actividad->id_plantilla);

	// Si ni hay primero, sale
	if(empty($nodoPlantilla)) return true;
	$nodosPlantilla[] = $nodoPlantilla;
	
	// Busco siguientes
	while ($nodoPlantilla = $nodoPlantillaDAO->getNodoSiguiente($nodoPlantilla->id)) {
		$nodosPlantilla[] = $nodoPlantilla;
	}
	
	// Listo los nodos, agrego los modulos
	foreach($nodosPlantilla as $nodo) {
		$contadorNombreCarpeta++;
		$tipoFiltro = new TipoFiltro();
		$tipoFiltro->id = $nodo->id_tipo_filtro;
		$tipoFiltro->load();
		if(empty($tipoFiltro->clase_handler)) {
			registrarLog(BATCH_LOG_ERROR,"Nodo de plantilla ".$actividad->nombre." sin filtro asociado");
			guardarFinalizacionProcesamiento($actividad,TIPO_PROCESO_RECOLECCION);
			finalizarPorError();
		}
		$archivoHandler = BASE_DIR."clases/modulos/instalados/clase.".$tipoFiltro->clase_handler.".php";
		// Valido que exista el archivo del modulo
		if(!file_exists($archivoHandler)) {
			registrarLog(BATCH_LOG_ERROR,"Modulo ".$tipoFiltro->clase_handler." no encontrado en clases/modulos/instalados");
			guardarFinalizacionProcesamiento($actividad,TIPO_PROCESO_RECOLECCION);
			finalizarPorError();
		}
		include_once($archivoHandler);
		
		// Valido que exista la clase
		if(!class_exists($tipoFiltro->clase_handler)) {
			registrarLog(BATCH_LOG_ERROR,"Clase no definida ".$tipoFiltro->clase_handler);
			guardarFinalizacionProcesamiento($actividad,TIPO_PROCESO_RECOLECCION);
			finalizarPorError();
		}
		
		$nombreClase = $tipoFiltro->clase_handler;
		$filtro = new $nombreClase();
		
		// Valido que implemente Recolector
		if(!is_subclass_of($filtro,"Filtro")) {
			registrarLog(BATCH_LOG_ERROR,"Clase definida ".$tipoFiltro->clase_handler." no implementa Filtro");
			guardarFinalizacionProcesamiento($actividad,TIPO_PROCESO_RECOLECCION);
			finalizarPorError();
		}
		
		if(!($filtro->_setCarpetaDestino($_config['pathBaseArchivosTemporales'].$nombreClase))) {
			guardarFinalizacionProcesamiento($actividad,TIPO_PROCESO_RECOLECCION);
			finalizarPorError();
		}
		$filtro->idNodoPlantilla = $nodo->id;
		$filtro->preparar($actividad);
		$modulos['filtros'][] = $filtro;
	}
	$modulos['filtros'][sizeof($modulos['filtros'])-1]->ultimoDeLaPlantilla = TRUE_;
	
}

function cargarEnviadores($actividad) {
	global $modulos,$_config;
	$modulos['enviadores'] = array();

	$actividadEnvioDAO = new ActividadEnvioDAO();
	$actividadEnvios = $actividadEnvioDAO->findInmediatosByActividad($actividad->id);

	
	foreach($actividadEnvios as $actividadEnvio) {
		// Valido que no se este ejecutando el envio
		$tecnologiaEnviador = new TecnologiaEnviador();
		$tecnologiaEnviador->id = $actividadEnvio->id_tecnologia_enviador;
		$tecnologiaEnviador->load();
		if(empty($tecnologiaEnviador->clase_handler)) {
			registrarLog(BATCH_LOG_ERROR,"Enviador de la actividad ".$actividad->nombre." sin tecnologia de envio asociado");
			guardarFinalizacionProcesamiento($actividad,TIPO_PROCESO_RECOLECCION);
			finalizarPorError();
		}
		
		$archivoHandler = BASE_DIR."clases/modulos/instalados/clase.".$tecnologiaEnviador->clase_handler.".php";
		// Valido que exista el archivo del modulo
		if(!file_exists($archivoHandler)) {
			registrarLog(BATCH_LOG_ERROR,"Modulo ".$tecnologiaEnviador->clase_handler." no encontrado en clases/modulos/instalados");
			guardarFinalizacionProcesamiento($actividad,TIPO_PROCESO_RECOLECCION);
			finalizarPorError();
		}
		include_once($archivoHandler);
		
		// Valido que exista la clase
		if(!class_exists($tecnologiaEnviador->clase_handler)) {
			registrarLog(BATCH_LOG_ERROR,"Clase no definida ".$tecnologiaEnviador->clase_handler);
			guardarFinalizacionProcesamiento($actividad,TIPO_PROCESO_RECOLECCION);
			finalizarPorError();
		}
		
		$nombreClase = $tecnologiaEnviador->clase_handler;
		$enviador = new $nombreClase();
		
		// Valido que implemente Recolector
		if(!is_subclass_of($enviador,"Enviador")) {
			registrarLog(BATCH_LOG_ERROR,"Clase definida ".$tecnologiaEnviador->clase_handler." no implementa Enviador");
			guardarFinalizacionProcesamiento($actividad,TIPO_PROCESO_RECOLECCION);
			finalizarPorError();
		}
		
		$enviador->_setCarpetaDestino($_config['pathBaseArchivosTemporales'].$nombreClase);
		$enviador->idActividadEnvio = $actividadEnvio->id;
		$enviador->preparar($actividadEnvio);
		$modulos['enviadores'][] = $enviador;
	}
	
}

function RealizarRecoleccionesPendientes($actividad) {
	$tareaDAO = new TareaDAO();
	$tareasPendientes = $tareaDAO->FilterByPendientesPorActividad($actividad->id);
	if(empty($tareasPendientes)) return true;
	registrarLog(BATCH_LOG_DEBUG,"Recolectado tareas pendientes");
	
	foreach($tareasPendientes as $tarea) {
		registrarLog(BATCH_LOG_DEBUG,"Recolectado tarea pendiente ".$tarea->id);
		$actividadEjecutada = new ActividadEjecutada($tarea->id_actividad_ejecutada);
		$modulos = unserialize($actividadEjecutada->modulos);
		registrarLog(BATCH_LOG_DEBUG,"Comenzando recoleccion");
		$recolector = $modulos['recolector'];
		$filtros = $modulos['filtros'];
		$archivos = array();
		$archivos[] = array('archivoOriginal'=>$tarea->nombre_original,
						  'archivoTemporal'=>"",
						  'ubicacionOriginal'=>$tarea->ubicacion_original,
						  'ubicacionTemporal'=>$recolector->_getCarpetaDestino(),
						  'idTarea'=>$tarea->id,
						  'tamanio'=>$tarea->tamanio);
		
		$recolector->recolectarActividad($actividadEjecutada,$archivos);
		$listaArchivos = limpiarVectorIndicesSalteados($recolector->_getListaArchivosOk());
		registrarLog(BATCH_LOG_DEBUG,"Comenzando filtrado");
		foreach($filtros as $filtro) {
			$filtro->_setArchivosAFiltrar($listaArchivos);
			$filtro->filtrarArchivos($actividadEjecutada);
			$listaArchivos = limpiarVectorIndicesSalteados($filtro->_getArchivosOk);
		}
		registrarLog(BATCH_LOG_DEBUG,"Fin de recoleccion de tarea pendiente ".$tarea->id);
				
	}
	registrarLog(BATCH_LOG_DEBUG,"Fin de recoleccion de tareas pendientes");
	
}


function procesarActividad() {
	global $argv,$modulos;
	
	// Valido id de actividad pasado
	$idAct = $argv[2];
	if(!is_numeric($idAct)) {
		registrarLog(BATCH_LOG_ERROR,"Parametro incorrecto - Probablemente haya quedado una tarea marcada como proceso en ejecucion, pero ya termino");
		finalizarPorError();
	}
	
	// Busco la actividad
	$actividadDAO = new ActividadDAO();
	$actividad = $actividadDAO->getById($idAct);
	
	if(empty($actividad)) {
		registrarLog(BATCH_LOG_ERROR,"numero de actividad incorrecto $idAct");
		registrarLog(BATCH_LOG_ERROR,"Parametro incorrecto - Probablemente haya quedado una tarea marcada como proceso en ejecucion, pero ya termino");
		finalizarPorError();
	}
	
	RealizarRecoleccionesPendientes($actividad);
	
	cargarRecolector($actividad);
	cargarFiltros($actividad);
	cargarEnviadores($actividad);
	
	// Creo una actividad ejecutada
	$fecha = new Fecha();
	$fecha->loadFromNow();
	$actividadEjecutada = new ActividadEjecutada();
	$actividadEjecutada->id_actividad = $actividad->id;
	$actividadEjecutada->fecha = $fecha->toString();
	$actividadEjecutada->modulos = serialize($modulos);
	$actividadEjecutada->baja_logica = FALSE_;
	$actividadEjecutada->pid = posix_getpid();
	$actividadEjecutada->save(); 

	$recolector = $modulos['recolector'];
	$recolector->_setCarpetaDestino($recolector->getCarpetaDestino()."/".$actividadEjecutada->id."/");
	$recolector->actividadEjecutada = $actividadEjecutada;
	
	$filtros = $modulos['filtros'];
	// Esta variable sirve por si hay 2 veces o más el mismo filtro en la misma plantilla
	$contadorNombreCarpeta = 0; 
	foreach($filtros as $filtro) {
		$contadorNombreCarpeta++;
		$filtro->_setCarpetaDestino($filtro->getCarpetaDestino()."/".$actividadEjecutada->id."_".$contadorNombreCarpeta."/");
		$filtro->actividadEjecutada = $actividadEjecutada;
	}
	
	$enviadores = $modulos['enviadores'];
	
	$actividadEjecutada->modulos = serialize($modulos);
	$actividadEjecutada->save(); 
	
	/* Motorsito en si */
	registrarLog(BATCH_LOG_DEBUG,"Comenzando recoleccion");
	$recolector->recolectarActividad($actividadEjecutada);
	$listaArchivos = limpiarVectorIndicesSalteados($recolector->_getListaArchivosOk());
	registrarLog(BATCH_LOG_DEBUG,"Comenzando filtrado");
	foreach($filtros as $filtro) {
		$filtro->_setArchivosAFiltrar($listaArchivos);
		$filtro->filtrarArchivos($actividadEjecutada);
		$listaArchivos = limpiarVectorIndicesSalteados($filtro->_getArchivosOk());
	}
	registrarLog(BATCH_LOG_DEBUG,"Comenzando envio");
	
	foreach($enviadores as $enviador) {
		$actividadEnvio = new ActividadEnvio($enviador->idActividadEnvio);
		ejecutarEnvio($actividad,$actividadEnvio,$enviador);		  
	}
	/* Fin del motorsito */
		
	guardarFinalizacionProcesamiento($actividad,TIPO_PROCESO_RECOLECCION);
}

function ejecutarEnvio($actividad,$actividadEnvio,$enviador) {
	$procesoEjecutandoseDAO = new ProcesoEjecutandoseDAO();
	$actEnv = $procesoEjecutandoseDAO->getByIdActividad($actividadEnvio->id,TIPO_PROCESO_ENVIO);
	if(!empty($actEnv)) {
		registrarLog(BATCH_LOG_ALERT,"Envío ya ejecutandose - ActividadEnvio ".$actividadEnvio->id);
		return false;
	}
	
	// Registro como ejecutandose
	$procesoEjecutandose = new ProcesoEjecutandose();
	$procesoEjecutandose->id_actividad = $actividadEnvio->id;
	$procesoEjecutandose->tipo = TIPO_PROCESO_ENVIO;
	$procesoEjecutandose->save();
	if(!$procesoEjecutandose->id) {
		registrarLog(BATCH_LOG_ALERT,"Envío no consiguió permisos de ejecucion exclusiva - ActividadEnvio ".$actividadEnvio->id);
	}
	
	$tareaDAO = new TareaDAO();
	
	registrarLog(BATCH_LOG_DEBUG,"Comenzando envio");
	$archivos = $tareaDAO->getArchivosAEnviar($actividad->id,$actividadEnvio->id);
	$idsTareas = array();
	foreach($archivos as $arc) $idsTareas[$arc['idTarea']] = $arc['idTarea'];

	$enviador->_setArchivosAEnviar($archivos);
	$enviador->enviarArchivos();
	
	foreach($archivos as $archivo) {
		$tarea = new Tarea($archivo['idTarea']);
		$tarea->recalcularEsperandoEnvio();
	}
	
	guardarFinalizacionProcesamiento($actividadEnvio,TIPO_PROCESO_ENVIO);	
}


function procesarEnvio() {
	global $argv,$modulos;
	
	// Valido id de actividad pasado
	$idActEnv = $argv[2];
	if(!is_numeric($idActEnv)) {
		registrarLog(BATCH_LOG_ERROR,"Parametro incorrecto - Probablemente haya quedado una tarea marcada como proceso en ejecucion, pero ya termino");
		finalizarPorError();
	}
	
		// Busco la actividad
	$actividadEnvioDAO = new ActividadEnvioDAO();
	$actividadEnvio = $actividadEnvioDAO->getById($idActEnv);
	
	if(empty($actividadEnvio)) {
		registrarLog(BATCH_LOG_ERROR,"numero de actividadEnvio incorrecto $idActEnv");
		registrarLog(BATCH_LOG_ERROR,"Parametro incorrecto - Probablemente haya quedado una tarea marcada como proceso en ejecucion, pero ya termino");
		finalizarPorError();
	}
	
	$actividad = new Actividad($actividadEnvio->id_actividad);
	if(!$actividad->id) {
		registrarLog(BATCH_LOG_ERROR,"numero de actividadEnvio incorrecto $idActEnv");
		guardarFinalizacionProcesamiento($actividadEnvio,TIPO_PROCESO_ENVIO);
		finalizarPorError();
		
	}
	
		/* COMIENZO DE PREPARO EL ENVIADO */
		// TODO: Refactorizar -> meter esto en un metodo 
	
	
		$tecnologiaEnviador = new TecnologiaEnviador();
		$tecnologiaEnviador->id = $actividadEnvio->id_tecnologia_enviador;
		$tecnologiaEnviador->load();
		if(empty($tecnologiaEnviador->clase_handler)) {
			registrarLog(BATCH_LOG_ERROR,"Enviador de la actividad ".$actividad->nombre." sin tecnologia de envio asociado");
			finalizarPorError();
		}
		
		$archivoHandler = BASE_DIR."clases/modulos/instalados/clase.".$tecnologiaEnviador->clase_handler.".php";
		// Valido que exista el archivo del modulo
		if(!file_exists($archivoHandler)) {
			registrarLog(BATCH_LOG_ERROR,"Modulo ".$tecnologiaEnviador->clase_handler." no encontrado en clases/modulos/instalados");
			finalizarPorError();
		}
		
		include_once($archivoHandler);
		
		// Valido que exista la clase
		if(!class_exists($tecnologiaEnviador->clase_handler)) {
			registrarLog(BATCH_LOG_ERROR,"Clase no definida ".$tecnologiaEnviador->clase_handler);
			finalizarPorError();
		}
		
		$nombreClase = $tecnologiaEnviador->clase_handler;
		$enviador = new $nombreClase();
		
		// Valido que implemente Recolector
		if(!is_subclass_of($enviador,"Enviador")) {
			registrarLog(BATCH_LOG_ERROR,"Clase definida ".$tecnologiaEnviador->clase_handler." no implementa Enviador");
			finalizarPorError();
		}
		
		$enviador->_setCarpetaDestino($_config['pathBaseArchivosTemporales'].$nombreClase);
		$enviador->idActividadEnvio = $actividadEnvio->id;
		$enviador->preparar($actividadEnvio);

		/* FIN DE PREPARO EL ENVIADO */
		// TODO: Refactorizar -> meter esto en un metodo 
		
		ejecutarEnvio($actividad,$actividadEnvio,$enviador);

}


$modulos = array();


function main() {
	global $argc,$argv;
	registrarLog(BATCH_LOG_DEBUG,"Lanzado un nucleo");
	
	// Valido cantidad de argumentos
	if($argc != 3) {
		registrarLog(BATCH_LOG_ERROR,"nucleo creado con parametros incorrectos");
		registrarLog(BATCH_LOG_ERROR,"Parametro incorrecto - Probablemente haya quedado una tarea marcada como proceso en ejecucion, pero ya termino");		
		finalizarPorError();
	}
	
	$metodo = "";
	switch($argv[1]) {
		case "recolectar":	$metodo = "procesarActividad";break;
		case "enviar": $metodo = "procesarEnvio";break;
	}
	
	
	if(empty($metodo)) {
		registrarLog(BATCH_LOG_ERROR,"parametro incorrecto, debe ser recolectar|tareamanual");
		registrarLog(BATCH_LOG_ERROR,"Parametro incorrecto - Probablemente haya quedado una tarea marcada como proceso en ejecucion, pero ya termino");
		finalizarPorError();
	}

	$metodo();
	
	// Finaliza el nucleo
	registrarLog(BATCH_LOG_DEBUG,"Finaliza el nucleo");
}


main();

?>