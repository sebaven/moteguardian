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
include_once(BASE_DIR."clases/batch/dao/dao.ProcesoEjecutandose.php");
include_once(BASE_DIR."clases/batch/entidades/clase.ProcesoEjecutandose.php");

// Negocio
include_once(BASE_DIR."clases/negocio/clase.TecnologiaEnviador.php");
include_once(BASE_DIR."clases/negocio/clase.Recoleccion.php");
include_once(BASE_DIR."clases/negocio/clase.Plantilla.php");
include_once(BASE_DIR."clases/negocio/clase.Envio.php");
include_once(BASE_DIR."clases/negocio/clase.Actividad.php");
include_once(BASE_DIR."clases/negocio/clase.TipoFiltro.php");
include_once(BASE_DIR."clases/negocio/clase.EstadoTarea.php");
include_once(BASE_DIR."clases/negocio/clase.RecoleccionEjecutada.php");

// DAOs
include_once(BASE_DIR."clases/dao/dao.Actividad.php");
include_once(BASE_DIR."clases/dao/dao.TecnologiaRecolector.php");
include_once(BASE_DIR."clases/dao/dao.Plantilla.php");
include_once(BASE_DIR."clases/dao/dao.NodoPlantilla.php");
include_once(BASE_DIR."clases/dao/dao.Recoleccion.php");
include_once(BASE_DIR."clases/dao/dao.Envio.php");
include_once(BASE_DIR."clases/dao/dao.Host.php");
include_once(BASE_DIR."clases/dao/dao.Tarea.php");
include_once(BASE_DIR."clases/dao/dao.Central.php");

// Módulos
include_once(BASE_DIR."clases/modulos/instalados/clase.ModuloRecolectorFTP.php");
include_once(BASE_DIR."clases/modulos/instalados/clase.ModuloEnviadorFTP.php");
include_once(BASE_DIR."clases/modulos/instalados/clase.ModuloFiltroValidador.php");
include_once(BASE_DIR."clases/modulos/instalados/clase.ModuloEnviadorLocal.php");


/**
 * Mueve una lista de archivos con el formato
 * [n]['archivoOriginal']="asdasd";
	 * [n]['archivoTemporal']="asdasd";
	 * [n]['ubicacionOriginal']="asdasd";
	 * [n]['ubicacionTemporal']="asdasd";
 	 * [n]['idTarea']=id_tarea;  
 */
function moverFicherosExito($listaArchivos) {	
	foreach($listaArchivos as $key=>$archivo){
		$archivo = $archivo['ubicacionTemporal']."/".$archivo['archivoTemporal'];
		moverArchivo($archivo,'exito');		
	}
}


/**
 * Mueve los archivos a las carpetas de exito, error y enviados
 */
function moverArchivo($archivo, $estado) {	
	$enviadorLocal = new ModuloEnviadorLocal();			
	$enviadorLocal->enviarArchivo($archivo, $estado);
}


/**
 * *
 * Deja constancia de que se termino el procesamiento de una recoleccion o un envio.
 * 
 * @param unknown_type $procesamiento Puede ser una recoleccion o un envio
 * @param unknown_type $tipo
 * @return unknown
 */
function guardarFinalizacionProcesamiento($procesamiento,$tipo) {
	$procesoEjecutandoseDAO = new ProcesoEjecutandoseDAO();
	$procesoEjecutandose = $procesoEjecutandoseDAO->getByIdRecoleccion($procesamiento->id,$tipo);
	if(empty($procesoEjecutandose)) {
		return false;
	}
	$procesoEjecutandose->delete();
	return true;	
}


/**
 * Carga un array con todos los recolectores asociados a la recoleccion
 *
 * @param $recoleccion (Recoleccion)
 */
function cargarRecolectores($recoleccion) {
	global $modulos,$_config;
	
	$modulos['recolectores'] = array();

	$centralDAO = new CentralDAO();
	if ($recoleccion->manual == TRUE_) {
		$centralesRecolectar = $centralDAO->getAsignadasRecoleccionManual($recoleccion->id);
	} else $centralesRecolectar = $centralDAO->findByIdRecoleccion($recoleccion->id);

	foreach($centralesRecolectar as $central) {
		// Busco el handler de recoleccion
		$tecnologiaRecolectorDAO = new TecnologiaRecolectorDAO();
		$tecnologiaRecolector = $tecnologiaRecolectorDAO->getByIdCentral($central->id);
		
		$archivoHandler = BASE_DIR."clases/modulos/instalados/clase.".$tecnologiaRecolector->clase_handler.".php";
		// Valido que exista el archivo del modulo
		if(!file_exists($archivoHandler)) {
			registrarLog(BATCH_LOG_ERROR,"Módulo ".$tecnologiaRecolector->clase_handler." no encontrado en clases/modulos/instalados");
			guardarFinalizacionProcesamiento($recoleccion->id,TIPO_PROCESO_RECOLECCION);
			finalizarPorError();
		}
		include_once($archivoHandler);
		
		// Valido que exista la clase
		if(!class_exists($tecnologiaRecolector->clase_handler)) {
			registrarLog(BATCH_LOG_ERROR,"Clase no definida ".$tecnologiaRecolector->clase_handler);
			guardarFinalizacionProcesamiento($recoleccion->id,TIPO_PROCESO_RECOLECCION);
			finalizarPorError();
		}
		
		$nombreClase = $tecnologiaRecolector->clase_handler;
		$recolector = new $nombreClase();
		
		// Valido que implemente Recolector
		if(!is_subclass_of($recolector,"Recolector")) {
			registrarLog(BATCH_LOG_ERROR,"Clase definida ".$tecnologiaRecolector->clase_handler." no implementa Recolector");
			guardarFinalizacionProcesamiento($recoleccion->id,TIPO_PROCESO_RECOLECCION);
			finalizarPorError();
		}
	
		if(!($recolector->_setCarpetaDestino($_config['pathBaseArchivosTemporales'].$nombreClase))) {
			guardarFinalizacionProcesamiento($recoleccion->id,TIPO_PROCESO_RECOLECCION);
			finalizarPorError();
		}
		$recolector->preparar($central);

		$modulos['recolectores'][] = $recolector;
	}
	
}



function cargarFiltros($actividad) {
	global $modulos,$_config;
	$modulos['filtros'] = array();

	$nodosPlantilla = array();
	
	$plantilla = new Plantilla();
	$plantilla->id = $actividad->id_plantilla;
	$plantilla->load();
	
	if(empty($plantilla->nombre)) {
		registrarLog(BATCH_LOG_ERROR,"Plantilla de la actividad ".$actividad->nombre." incorrecta");
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
			registrarLog(BATCH_LOG_ERROR,"Módulo ".$tipoFiltro->clase_handler." no encontrado en clases/modulos/instalados");
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
		
		// Valido que implemente Filtro
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



/**
 * Se cargan los enviadores que deben lanzarse inmediatamente
 * luego de una recoleccion
 *
 * @param $recoleccion
 */
function cargarEnviadores($recoleccion) {
	global $modulos,$_config;
	$modulos['enviadores'] = array();

	$envioDAO = new EnvioDAO();
	$envios = $envioDAO->findInmediatosByIdRecoleccion($recoleccion->id);
	foreach($envios as $envio) {
		// Se obtienen todos los hosts asociados al envio
		$hostDAO = New HostDAO();
		$hosts = $hostDAO->findByIdEnvio($envio->id);
		
		// Luego se carga en el array de enviadores cada uno de los enviadores asociados
		// a los hosts
		foreach($hosts as $host) {
			$tecnologiaEnviador = new TecnologiaEnviador();
			$tecnologiaEnviador->id = $host->id_tecnologia_enviador;
			$tecnologiaEnviador->load();
			if(empty($tecnologiaEnviador->clase_handler)) {
				registrarLog(BATCH_LOG_ERROR,"Enviador del host ".$host->nombre." perteneciente al envío ".$envio->nombre." sin tecnologia de envío asociado");
				guardarFinalizacionProcesamiento($recoleccion,TIPO_PROCESO_RECOLECCION);
				finalizarPorError();
			}
			
			$archivoHandler = BASE_DIR."clases/modulos/instalados/clase.".$tecnologiaEnviador->clase_handler.".php";
			// Valido que exista el archivo del modulo
			if(!file_exists($archivoHandler)) {
				registrarLog(BATCH_LOG_ERROR,"Módulo ".$tecnologiaEnviador->clase_handler." no encontrado en clases/modulos/instalados");
				guardarFinalizacionProcesamiento($recoleccion,TIPO_PROCESO_RECOLECCION);
				finalizarPorError();
			}
			include_once($archivoHandler);
			
			// Valido que exista la clase
			if(!class_exists($tecnologiaEnviador->clase_handler)) {
				registrarLog(BATCH_LOG_ERROR,"Clase no definida ".$tecnologiaEnviador->clase_handler);
				guardarFinalizacionProcesamiento($recoleccion,TIPO_PROCESO_RECOLECCION);
				finalizarPorError();
			}
			
			$nombreClase = $tecnologiaEnviador->clase_handler;
			$enviador = new $nombreClase();
			
			// Valido que implemente Enviador
			if(!is_subclass_of($enviador,"Enviador")) {
				registrarLog(BATCH_LOG_ERROR,"Clase definida ".$tecnologiaEnviador->clase_handler." no implementa Enviador");
				guardarFinalizacionProcesamiento($recoleccion,TIPO_PROCESO_RECOLECCION);
				finalizarPorError();
			}
			
			$enviador->_setCarpetaDestino($_config['pathBaseArchivosTemporales'].$nombreClase);
			$enviador->idEnvio = $envio->id;
			$enviador->preparar($host, $envio);
			$enviador->setNombreEnvio($envio->nombre);
			$modulos['enviadores'][] = $enviador;
		}	
	}	

}



function RealizarRecoleccionesPendientes($recoleccion) {
	$tareaDAO = new TareaDAO();
	$tareasPendientes = $tareaDAO->FilterByPendientesPorRecoleccion($recoleccion->id);
	if(empty($tareasPendientes)) return true;
	registrarLog(BATCH_LOG_DEBUG,"------- Recolectando tareas pendientes -------");
	
	foreach($tareasPendientes as $tarea) {
		registrarLog(BATCH_LOG_DEBUG,"Recolectando tarea pendiente ".$tarea->id);
		$recoleccionEjecutada = new RecoleccionEjecutada($tarea->id_recoleccion_ejecutada);
		$modulos = unserialize($recoleccionEjecutada->modulos);
		registrarLog(BATCH_LOG_DEBUG,"Comenzando recolección");
		
		// Se busca el recolector que trajo el fichero
		foreach($modulos['recolectores'] as $recolector) {
			if ($recolector->id_central == $tarea->id_central ) break; 
		}
		$filtros = $modulos['filtros'];
		$archivos = array();
		$archivos[] = array('archivoOriginal'=>$tarea->nombre_original,
						  'archivoTemporal'=>"",
						  'ubicacionOriginal'=>$tarea->ubicacion_original,
						  'ubicacionTemporal'=>$recolector->_getCarpetaDestino(),
						  'idTarea'=>$tarea->id,
						  'tamanio'=>$tarea->tamanio);
		
		$recolector->recolectar($recoleccionEjecutada,$archivos);
		$listaArchivos = limpiarVectorIndicesSalteados($recolector->_getListaArchivosOk());
		registrarLog(BATCH_LOG_DEBUG,"Comenzando filtrado");
		foreach($filtros as $filtro) {
			$filtro->_setArchivosAFiltrar($listaArchivos);
			$filtro->filtrarArchivos($recoleccionEjecutada);
			$listaArchivos = limpiarVectorIndicesSalteados($filtro->_getArchivosOk);
		}
		registrarLog(BATCH_LOG_DEBUG,"Fin de recolección de tarea pendiente ".$tarea->id);
				
	}
	registrarLog(BATCH_LOG_DEBUG,"------- Fin de recolección de tareas pendientes -------");
	
}



function procesarRecoleccion() {
	global $argv,$modulos;
	
	// Valido id de recoleccion pasado
	$idRec = $argv[2];
	if(!is_numeric($idRec)) {
		registrarLog(BATCH_LOG_ERROR,"Parámetro incorrecto - Probablemente haya quedado una tarea marcada como proceso en ejecución, pero ya terminó");
		finalizarPorError();
	}
	
	// Se busca la recoleccion
	$recoleccionDAO = new RecoleccionDAO();
	$recoleccion = $recoleccionDAO->getById($idRec);
	
	if(empty($recoleccion)) {
		registrarLog(BATCH_LOG_ERROR,"Número de recolección incorrecto " .$idRec);
		registrarLog(BATCH_LOG_ERROR,"Parámetro incorrecto - Probablemente haya quedado una tarea marcada como proceso en ejecución, pero ya terminó");
		finalizarPorError();
	}
	
	// Se intenta realizar las recolecciones que hayan quedado pendientes
	RealizarRecoleccionesPendientes($recoleccion);
	
	// Se cargan los recolectores...
	cargarRecolectores($recoleccion);

	// ...los filtros
	$actividadDAO = new ActividadDAO();
	$actividad = $actividadDAO->getById($recoleccion->id_actividad);
	// Puede ser una recolección manual o una sin actividad asociada
	if(isset($actividad->nombre)) {
		cargarFiltros($actividad);
	} else $modulos['filtros'] = array();
	
	// ...y los enviadores (si la recoleccion no es manual)
	if ($recoleccion->manual == FALSE_) {
		cargarEnviadores($recoleccion);
	} else $modulos['enviadores'] = array();
	
	// Creo una recolección ejecutada
	$fecha = new Fecha();
	$fecha->loadFromNow();
	$recoleccionEjecutada = new RecoleccionEjecutada();
	$recoleccionEjecutada->id_recoleccion = $recoleccion->id;
	$recoleccionEjecutada->fecha = $fecha->toString();
	$recoleccionEjecutada->modulos = serialize($modulos);
	$recoleccionEjecutada->baja_logica = FALSE_;
	$recoleccionEjecutada->pid = posix_getpid();
	$recoleccionEjecutada->save();

	$recolectores = $modulos['recolectores'];
	
	// Se asigna a cada recolector la carpeta de destino y la recoleccion ejecutada
	foreach ($recolectores as $recolector) {
		$recolector->_setCarpetaDestino($recolector->getCarpetaDestino()."/".$recoleccionEjecutada->id."/");
		$recolector->recoleccionEjecutada = $recoleccionEjecutada;	
	}
	
	$filtros = $modulos['filtros'];
	// Esta variable sirve por si aparecen 2 veces o mas el mismo filtro en la misma plantilla
	$contadorNombreCarpeta = 0; 
	foreach($filtros as $filtro) {
		$contadorNombreCarpeta++;
		$filtro->_setCarpetaDestino($filtro->getCarpetaDestino()."/".$recoleccionEjecutada->id."_".$contadorNombreCarpeta."/");
		$filtro->recoleccionEjecutada = $recoleccionEjecutada;
	}
	
	$enviadores = $modulos['enviadores'];
	
	$recoleccionEjecutada->modulos = serialize($modulos);
	$recoleccionEjecutada->save(); 
	
	
	foreach($recolectores as $recolector) {
		// Recoleccion		
		registrarLog(BATCH_LOG_DEBUG,"------- Comenzando recolección -------");
				
		$recolector->recolectar($recoleccionEjecutada);
		
		// Obtengo la lista de archivos recolectados exitosamente 
		$listaArchivos = limpiarVectorIndicesSalteados($recolector->_getListaArchivosOk());

		// Obtengo cantidades de archivos ok, error y total
		$archivosOk = sizeof($recolector->_getListaArchivosOk());
		$archivosError = sizeof($recolector->_getListaArchivosError());
		$archivosTotal = $archivosOk + $archivosError;
		
		if($archivosTotal > 0)	$strLog = "------- Finalizando recolección: Archivos detectados: ".$archivosTotal." - OK:".$archivosOk." (".($archivosOk*100/$archivosTotal)."%) - Error:".$archivosError." (".($archivosError*100/$archivosTotal)."%) -------";			
		else $strLog = "------- Finalizando recolección: Archivos detectados: 0 -------";
		registrarLog(BATCH_LOG_DEBUG,$strLog);
		// Fin de recoleccion		
		
		// Filtrado		
		if($archivosTotal > 0) {
			$nroFiltrado = 1;
			foreach($filtros as $filtro) {
				registrarLog(BATCH_LOG_DEBUG,"------- Comenzando filtrado ".$nroFiltrado."-------");
								
				$filtro->_setArchivosAFiltrar($listaArchivos);
				$filtro->filtrarArchivos($recoleccionEjecutada);
				
				// Creo la lista de archivos filtrados exitosamente
				$listaArchivos = limpiarVectorIndicesSalteados($filtro->_getArchivosOk());
				
				// Obtengo cantidades de archivos ok, error y total
				$archivosOk = sizeof($filtro->_getArchivosOk());
				$archivosError = sizeof($filtro->_getArchivosError());
				$archivosTotal = $archivosOk + $archivosError;
				
				// Escribo en el log la cantidad de ficheros procesados por el filtro				
				$strLog = "------- Finalizando filtrado: Archivos procesados: ".$archivosTotal. " - ";				
				$strLog.= "OK:".$archivosOk;
				if($archivosTotal>0) $strLog.="(".($archivosOk*100/$archivosTotal)."%)";				
				$strLog.= " - Error:".$archivosError;
				if($archivosTotal>0) $strLog.="(".($archivosError*100/$archivosTotal)."%)";				
				$strLog.= " -------";								
				registrarLog(BATCH_LOG_DEBUG,$strLog);
								
				$nroFiltrado++;			
			}			
			moverFicherosExito($listaArchivos);							
		}		
		// Fin de filtrado
	}
	
	// Envios	
	$enviadoresEjecutados = 1;
	$cantEnviadores = count($enviadores);
	$masEnvios = TRUE_;
	foreach($enviadores as $enviador) {					
		registrarLog(BATCH_LOG_DEBUG,"------- Comenzando envío ".$enviador->getNombreEnvio()."-------");
		
		$envio = new Envio($enviador->idEnvio);
		$host = new Host($enviador->idHost);
		registrarLog(BATCH_LOG_DEBUG,"--- Host ID: ".$enviador->idHost." Nombre: ".$host->nombre." ---");
		if ($enviadoresEjecutados >= $cantEnviadores) {
			$masEnvios = FALSE_;
		}
		ejecutarEnvio($recoleccion,$envio,$enviador,$masEnvios);
		$enviadoresEjecutados++;
		
		$archivosOk = sizeof($enviador->archivosOk);
		$archivosError = sizeof($enviador->archivosError);
		$archivosTotal = $archivosOk + $archivosError;
		$strLog = "------- Finalizando envío a Host: ".$host->nombre." - Archivos que se intentaron enviar:".($archivosTotal). " - ";
		$strLog.= "OK:".$archivosOk;
		if($archivosTotal>0) $strLog.="(".($archivosOk*100/$archivosTotal)."%)";
		$strLog.= " - Error:".$archivosError;
		if($archivosTotal>0) $strLog.="(".($archivosError*100/$archivosTotal)."%)";
		$strLog.= " -------"; 
		
		registrarLog(BATCH_LOG_DEBUG,$strLog);
	}	
	// Fin de Envios	
		
	guardarFinalizacionProcesamiento($recoleccion,TIPO_PROCESO_RECOLECCION);
}





function ejecutarEnvio($recoleccion,$envio,$enviador,$masEnvios) {
	$procesoEjecutandoseDAO = new ProcesoEjecutandoseDAO();
	$actEnv = $procesoEjecutandoseDAO->getByIdRecoleccion($recoleccion->id,TIPO_PROCESO_ENVIO);
	if(!empty($actEnv)) {
		registrarLog(BATCH_LOG_ALERT,"Envío ya ejecutándose - Envio ".$envio->id);
		return false;
	}
	
	// Registro como ejecutandose
	$procesoEjecutandose = new ProcesoEjecutandose();
	$procesoEjecutandose->id_recoleccion = $recoleccion->id;
	$procesoEjecutandose->tipo = TIPO_PROCESO_ENVIO;
	$procesoEjecutandose->save();
	if(!$procesoEjecutandose->id) {
		registrarLog(BATCH_LOG_ALERT,"Envío no consiguió permisos de ejecución exclusiva - Envio ".$envio->id);
	}
	
	$tareaDAO = new TareaDAO();
	registrarLog(BATCH_LOG_DEBUG,"Comenzando envío");
	$archivos = $tareaDAO->getArchivosAEnviar($recoleccion->id,$envio->id);
	$cantidadArchivos = count($archivos);
	registrarLog(BATCH_LOG_DEBUG,"Cantidad de archivos a enviar: ".$cantidadArchivos);
	
	$enviador->_setArchivosAEnviar($archivos);
	$enviador->enviarArchivos($masEnvios);
	
	foreach($archivos as $archivo) {
		$tarea = new Tarea($archivo['idTarea']);
		$tarea->recalcularEsperandoEnvio($masEnvios);
		
		// ENVIO A CARPETA DE ENVIADOS
		if($tarea->esperando_envio == FALSE_) {
			moverArchivo($tareaDAO->getPathArchivoValidado($tarea->id), 'enviado');
		}		
	}	
	
	// TODO: Va a haber que revisar esto...me parece que habría que agregar una columna "id_envio" en la tabla de proceso ejecutandose
	guardarFinalizacionProcesamiento($recoleccion,TIPO_PROCESO_ENVIO);	
}



function ejecutarEnvioManual($envio,$enviador) {

	// Registro como ejecutandose
	$procesoEjecutandose = new ProcesoEjecutandose();
	// TODO: Probar si esto anda, si va bien, cambiar el nombre de la columna "id_recoleccion" a "id_proceso"
	$procesoEjecutandose->id_recoleccion = $envio->id;
	$procesoEjecutandose->tipo = TIPO_PROCESO_ENVIO;
	$procesoEjecutandose->save();
	if(!$procesoEjecutandose->id) {
		registrarLog(BATCH_LOG_ALERT,"Envío no consiguió permisos de ejecución exclusiva - Envio ".$envio->id);
	}
	
	$tareaDAO = new TareaDAO();
	registrarLog(BATCH_LOG_DEBUG,"Comenzando envío");
	$archivos = $tareaDAO->getArchivosAEnviarManualmente($envio->id);

	$enviador->_setArchivosAEnviar($archivos);
	$enviador->enviarArchivos(FALSE_);
	
//	foreach($archivos as $archivo) {
//		$tarea = new Tarea($archivo['idTarea']);
//		$tarea->recalcularEsperandoEnvio();
//		
//		// ENVIO A CARPETA DE ENVIADOS
//		if($tarea->esperando_envio == FALSE_) {
//			moverArchivo($tareaDAO->getPathArchivoValidado($tarea->id), 'exito');
//		}		
//	}	
	
	// TODO: Va a haber que revisar esto...me parece que habría que agregar una columna "id_envio" en la tabla de proceso ejecutandose
	// o generalizar la de "id_recoleccion" a algo como "id_proceso"
	guardarFinalizacionProcesamiento($envio,TIPO_PROCESO_ENVIO);	
}



function procesarEnvio() {
	global $argv,$modulos;
	
	// Valido id de envío pasado
	$idEnv = $argv[2];
	if(!is_numeric($idEnv)) {
		registrarLog(BATCH_LOG_ERROR,"Parámetro incorrecto - Probablemente haya quedado una tarea marcada como proceso en ejecución, pero ya terminó");
		finalizarPorError();
	}
	
	registrarLog(BATCH_LOG_DEBUG,"------- Procesando envío ID: ".$idEnv."-------");
	
	// Busco el envío
	$envioDAO = new EnvioDAO();
	$envio = $envioDAO->getById($idEnv);
	
	if(empty($envio)) {
		registrarLog(BATCH_LOG_ERROR,"Número de Envio incorrecto $idEnv");
		registrarLog(BATCH_LOG_ERROR,"Parámetro incorrecto - Probablemente haya quedado una tarea marcada como proceso en ejecución, pero ya terminó");
		finalizarPorError();
	}
	
	// Si el envío no es manual, se obtiene la recoleccion asociada
	if ($envio->manual == FALSE_) {
		$recoleccion = new Recoleccion($envio->id_recoleccion);
		if((!$recoleccion->id)) {
			registrarLog(BATCH_LOG_ERROR,"Número de Envio incorrecto $idEnv");
			guardarFinalizacionProcesamiento($envio,TIPO_PROCESO_ENVIO);
			finalizarPorError();
		}
	}
	
	// Se obtienen todos los hosts asociados al envio
	$hostDAO = new HostDAO();
	if ($envio->manual == TRUE_) {
		$hosts = $hostDAO->findByIdEnvioManual($envio->id);
	} else {
		$hosts = $hostDAO->findByIdEnvio($envio->id);
	}
	
	$enviadoresEjecutados = 1;
	$masEnvios = TRUE_;
	foreach ($hosts as $host) {
		/* COMIENZA EL PREPARADO DEL ENVIADOR */
		// TODO: Refactorizar -> meter esto en un metodo 
	
		$tecnologiaEnviador = new TecnologiaEnviador();
		$tecnologiaEnviador->id = $host->id_tecnologia_enviador;
		$tecnologiaEnviador->load();
		if(empty($tecnologiaEnviador->clase_handler)) {
			registrarLog(BATCH_LOG_ERROR,"Enviador del envio ".$envio->nombre." sin tecnología de envío asociado");
			finalizarPorError();
		}
		
		$archivoHandler = BASE_DIR."clases/modulos/instalados/clase.".$tecnologiaEnviador->clase_handler.".php";
		// Valido que exista el archivo del modulo
		if(!file_exists($archivoHandler)) {
			registrarLog(BATCH_LOG_ERROR,"Módulo ".$tecnologiaEnviador->clase_handler." no encontrado en clases/modulos/instalados");
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
		
		// Valido que implemente Enviador
		if(!is_subclass_of($enviador,"Enviador")) {
			registrarLog(BATCH_LOG_ERROR,"Clase definida ".$tecnologiaEnviador->clase_handler." no implementa Enviador");
			finalizarPorError();
		}
		
		$enviador->_setCarpetaDestino($_config['pathBaseArchivosTemporales'].$nombreClase);
		$enviador->idEnvio = $envio->id;
		$enviador->idHost = $host->id;
		$enviador->preparar($host,$envio);

		/* FIN DE PREPARO EL ENVIADO */
		// TODO: Refactorizar -> meter esto en un metodo 
		
		// Se lanza el envío tradicional o el manual según corresponda
		if ($envio->manual == TRUE_) {
			ejecutarEnvioManual($envio,$enviador);
		} else {
			if ($enviadoresEjecutados >= count($hosts)) $masEnvios = FALSE_;
			ejecutarEnvio($recoleccion,$envio,$enviador,$masEnvios);
			$enviadoresEjecutados++;
		}
			
	}		

}






// COMIENZA LA EJECUCIÓN


$modulos = array();


function main() {
	global $argc,$argv;
	registrarLog(BATCH_LOG_DEBUG,"Lanzado un nucleo");
	
	// Valido cantidad de argumentos
	if($argc != 3) {
		registrarLog(BATCH_LOG_ERROR,"Núcleo creado con parametros incorrectos");
		registrarLog(BATCH_LOG_ERROR,"Parámetro incorrecto - Probablemente haya quedado una tarea marcada como proceso en ejecución, pero ya terminó");		
		finalizarPorError();
	}
	
	
	$metodo = "";
	switch($argv[1]) {
		case "recolectar":	$metodo = "procesarRecoleccion";break;
		case "enviar": $metodo = "procesarEnvio";break;
	}
	
	
	if(empty($metodo)) {
		registrarLog(BATCH_LOG_ERROR,"Parametro incorrecto, debe ser recolectar|tareamanual");
		registrarLog(BATCH_LOG_ERROR,"Parametro incorrecto - Probablemente haya quedado una tarea marcada como proceso en ejecución, pero ya terminó");
		finalizarPorError();
	}
	$metodo();
	
	// Finaliza el nucleo
	registrarLog(BATCH_LOG_DEBUG,"Finaliza el nucleo");
}

main();

?>