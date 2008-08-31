<?php
error_reporting(4);

define("BASE_DIR_RELATIVO","../");

define("BASE_DIR",realpath(BASE_DIR_RELATIVO)."/");

include_once(BASE_DIR."comun/defines_app.php");
include_once(BASE_DIR."ionix/config/ionix-config.php");
include_once(BASE_DIR."ionix/data/Db.php"); 
include_once(BASE_DIR."ionix/data/DbMySql.php"); 
include_once(BASE_DIR."ionix/data/ConnectionManager.php");
include_once(BASE_DIR."ionix/data/AbstractEntity.php");
include_once(BASE_DIR."ionix/data/AbstractDAO.php");
include_once BASE_DIR."clases/modulos/instalados/clase.ModuloRecolectorFTP.php";
include_once BASE_DIR."clases/negocio/clase.Actividad.php";
include_once BASE_DIR."clases/negocio/clase.DestinoFTP.php";
include_once BASE_DIR."ionix/data/ConnectionManager.php";
include_once BASE_DIR."comun/inc.global.php";
include_once BASE_DIR."batch/funciones.php";
include_once BASE_DIR."comun/Fecha.php";
include_once BASE_DIR."clases/util/clase.FTPDriver.php";

       //Creo la Actividad a procesar
       $actividad1 = new Actividad(1);       
       //Genero el modulo que procesara la recoleccion de esa Actividad1
       $moduloRecolectorFTP = new ModuloRecolectorFTP();
       $moduloRecolectorFTP->preparar($actividad1);
       $resulRecolector=$moduloRecolectorFTP->recolectarArchivos();
       var_dump($resulRecolector);
       exit;

?>