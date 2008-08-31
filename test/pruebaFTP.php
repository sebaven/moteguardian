<?php

ini_set("max_execution_time","6000");
ob_start();
	define("BASE_DIR_RELATIVO","../");
	define("BASE_DIR",realpath(BASE_DIR_RELATIVO)."/");
	
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
	include_once BASE_DIR."/clases/util/clase.FTPDriver.php";
	include_once BASE_DIR."/batch/funciones.php";
	
     //OBTENER UN ARCHIVO
     $ftpInst2 = new FTPDriver ();
     $id_con = 0;
     echo 'ID_CONEXION_ANTES_DE_CONECTARSE='.$id_con;
	 echo "<BR>";
     $id_con=$ftpInst2->conexion("200.69.243.17","ftp_factory","Facturitas");
     echo 'ID_CONEXION_DESPUES_DE_CONECTARSE=';var_dump($id_con);
   	 echo "<BR>";
     $archivo_remoto = './LOST/lost.s04e02.avi';
	 $archivo_local = 'C:\Documents and Settings\mmartini\Escritorio\lost.s04e02.avi';
     // abrir algún archivo para escritura
     $gestor = fopen($archivo_local, 'w');	
     echo 'RES_ANTES_DE_SACAR_EL_CABLE_Y_DE_HACER_LA_OPERACION='.$res;
   	 echo "<BR>";
   	 ob_flush();
     $res=$ftpInst2->obtenerArchivo($id_con,$gestor,$archivo_remoto);
     echo 'ID_CONEXION_DESPUES_DE_SACAR_EL_CABLE=';var_dump($id_con);
   	 echo "<BR>";
     echo 'RES_DESPUES_DE_SACAR_EL_CABLE=';var_dump($res);
   	 echo "<BR>";
     $ftpInst2->cerrarConexion($id_con);
     exit;
	
	 //LISTADO DE ARCHIVOS
	 $ftpInst1 = new FTPDriver ();
     $id_con=$ftpInst1->conexion("200.69.243.17","ftp_factory","Facturitas");
     $archivos = array();
     $archivos=$ftpInst1->listarNORecursivamente($id_con,".");
     printr($archivos);
     $ftpInst1->listarRecursivamente($id_con,".",$archivos);
     printr($archivos);
     exit;

     
	 //LISTADO DE ARCHIVOS
	 $ftpInst1 = new FTPDriver ();
     $id_con=$ftpInst1->conexion("200.69.243.17","ftp_factory","Facturitas");
     $contenidos=$ftpInst1->listadoArchivos($id_con,'./hockey/');
     $ftpInst1->cerrarConexion($id_con);
     print_r($contenidos);
     

     //AGREGAR UN ARCHIVO
     $ftpInst3 = new FTPDriver ();
     $id_con=$ftpInst3->conexion("200.69.243.17","ftp_factory","Facturitas");
     //abrir algun archivo para lectura
     $archivoRemoto = 'somefileREMOTO.txt';
     $archivoLocal = 'C:\Documents and Settings\mmartini\Escritorio\somefileLOCALL.txt';
     $apuntador = fopen($archivoLocal, 'r');
     $res=$ftpInst3->agregarArchivo($id_con,$archivoRemoto,$apuntador);
     $ftpInst3->cerrarConexion($id_con);
     
     
?>