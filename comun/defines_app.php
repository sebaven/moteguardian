<?php
define('ROL_ID_ADMIN', '1');
define('ROL_ID_USUARIO','2');
define('TRUE_', 1);
define('FALSE_', 0);

/* Constantes de la tabla de configuracion */
define("CONST_CONFIG_ULTIMA_EJECUCION_DISPATCHER",1);
define("CONST_CONFIG_ALMACENAMIENTO_DISCO",2);
define("CONST_CONFIG_ALMACENAMIENTO_BD",3);
define("CONST_CONFIG_ESTADO_SRDFIP",4);
define("CONST_CONFIG_UBICACION_FICHEROS_EXITO",5);
define("CONST_CONFIG_UBICACION_FICHEROS_ERROR",6);
define("CONST_CONFIG_UBICACION_FICHEROS_BASE",7);
define("CONST_CONFIG_UBICACION_FICHEROS_ENVIADOS",8);

/* Constantes de la tabla de configuracion con los datos del origen y destino FTP local */
define("CONST_CONFIG_FTP_LOCAL_IP",9);
define("CONST_CONFIG_FTP_LOCAL_UBICACION_RECOLECCION",10);
define("CONST_CONFIG_FTP_LOCAL_UBICACION_ENVIO",11);
define("CONST_CONFIG_FTP_LOCAL_PUERTO",12);
define("CONST_CONFIG_FTP_LOCAL_INTENTOS",13);
define("CONST_CONFIG_FTP_LOCAL_USUARIO",14);
define("CONST_CONFIG_FTP_LOCAL_PASSWORD",15);
define("CONST_CONFIG_FTP_LOCAL_TIMEOUT",16);
define("CONST_CONFIG_FTP_LOCAL_MODO_PASIVO",TRUE_);
define("CONST_CONFIG_FTP_LOCAL_MODO_GIT",FALSE_);


/* Constantes de dia para la configuracion */
define("CONST_DIA_LUNES","Lunes");
define("CONST_DIA_MARTES","Martes");
define("CONST_DIA_MIERCOLES","Miércoles");
define("CONST_DIA_JUEVES","Jueves");
define("CONST_DIA_VIERNES","Viernes");
define("CONST_DIA_SABADO","Sábado");
define("CONST_DIA_DOMINGO","Domingo");

/* Constantes de los estados de srdfip */
define("ESTADO_SRDFIP_LEVANTANDO",1);
define("ESTADO_SRDFIP_EJECUTANDO",2);
define("ESTADO_SRDFIP_DETENIENDO",3);
define("ESTADO_SRDFIP_DETENIDO",4);



?>