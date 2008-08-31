<?php
/**
 * @date 20/05/2008
 * @version 1.0
 * @author glerendegui
 */


define("BASE_DIR_RELATIVO","./");
include_once(BASE_DIR_RELATIVO."batch/global.php");

include_once(FWK_DIR."data/AbstractDAO.php");
include_once(FWK_DIR."data/AbstractEntity.php");
include_once(BASE_DIR."clases/dao/dao.Configuracion.php");
include_once(BASE_DIR."clases/dao/dao.Planificacion.php");
include_once(BASE_DIR."clases/batch/dao/dao.ProcesoEjecutandose.php");
include_once(BASE_DIR."clases/batch/entidades/clase.ProcesoEjecutandose.php");

function borrarTareasEjecutandose() {
	$procesoEjecutandoseDAO = new ProcesoEjecutandoseDAO();
	$procesoEjecutandoseDAO->borrarTareasEjecutandose();
}


function main() {
	registrarLog(BATCH_LOG_ALERT,"Comenzando ejecución de srdf");
	borrarTareasEjecutandose();
}


main();

?>