<?php

define("BASE_DIR_RELATIVO","../");

include_once(BASE_DIR."comun/defines_app.php");
include_once(BASE_DIR."ionix/config/ionix-config.php");
include_once(BASE_DIR."ionix/data/Db.php"); 
include_once(BASE_DIR."ionix/data/DbMySql.php"); 
include_once(BASE_DIR."ionix/data/ConnectionManager.php");
include_once(BASE_DIR."ionix/data/AbstractEntity.php");
include_once(BASE_DIR."ionix/data/AbstractDAO.php");
include_once BASE_DIR."clases/modulos/instalados/clase.ModuloEnviadorFTP.php";
include_once BASE_DIR."clases/negocio/clase.ActividadEnviador.php";
include_once BASE_DIR."clases/negocio/clase.DestinoFTP.php";
include_once BASE_DIR."ionix/data/ConnectionManager.php";
include_once BASE_DIR."comun/inc.global.php";
include_once BASE_DIR."batch/funciones.php";
include_once BASE_DIR."comun/Fecha.php";

       
	   /*print(validarSHA1('C:\Documents and Settings\mmartini\Escritorio\LOCAL_FILE\Archivo1.txt','6cc76bddf29e934258e30fbf301ad8ec89cb7cc6'));	
	   $fecha= new Fecha();
	   $fecha->loadFromNow();
	   $AAMMDD= $fecha->dateToString();	
	   renombradoArchivo('ArchivoOriginal.txt','TTT%C_TZ.%T_%d%m%a_VAL%','C153','12',$fecha);	*/
       $moduloEnviadorFTP = new ModuloEnviadorFTP();
       $ActividadEnvio= new ActividadEnviador();
	   $ActividadEnvio->id=1;
	   $moduloEnviadorFTP->enviarArchivos($ActividadEnvio,NULL);

?>