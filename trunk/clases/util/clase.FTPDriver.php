<?php
ini_set("max_execution_time","1000000");

class FTPDriver {

	/**
	 * Establece la conceccion al servidor de FTP
	 *
	 * @param $servidor_ftp=host  $user=usuario $pass=password $modo=de conexxion por defecto en true
	 * @return $id_con identificador de la conexion FTP
	 */
	function conexion ($servidor_ftp, $user, $pass, $modo=true, $puerto=21, $tiempoEspera=360){
		// configurar una conexion o abortar
		// el arroba es para evitar los warning que salgan por pantalla
		if(empty($tiempoEspera)) $tiempoEspera = 90;
		@$id_con = ftp_connect($servidor_ftp,$puerto,$tiempoEspera);

		if ((!$id_con) || (!isset($id_con))){
			return false; //return false conexion no establecida
		}
		else{
			// Abrir la session con usuario y contraseña
			// el arroba es para evitar los warning que salgan por pantalla
			@$login_result = ftp_login($id_con, $user, $pass);
			if ((!$login_result) || (!isset($login_result))){
				return false; //return false el logueo no fue establecida
			}
		}


		// Habilita o deshabilita el modo pasivo. En modo pasivo, las conexiones de datos son
		// iniciadas por el cliente, en lugar del servidor. Puede requerirse si el cliente se
		// encuentra detrás de un firewall. ftp_pasv() únicamente puede llamarse después de
		// un inicio de sesión exitoso, de otra forma fallará.
		ftp_pasv ($id_con, $modo);

		// Verifico que type UNIX (tambien lo toman como valor la mayoria de los servidores windows)
		$res = ftp_systype($id_con);
		//if($res != "UNIX") return false;

		// Se conect OK
		return $id_con;
	}

	/**
	 * Agrega un archivo al servidor de FTP
	 * MODO DE USO:
	 *			  //abrir algun archivo para lectura
	 *		   	    $archivo = 'somefile.txt';
	 * 				$apuntador = fopen($archivo, 'r');
	 * @param $id_con=identificador de la conexion FTP
	 * @param $archivo=nombre del archivo remoto qcon el que lo llamaremos
	 * @param $apuntador= un apuntador de archivo abierto sobre el archivo local. La lectura se detiene al final del archivo.
	 * @param $tipo_archivo=El modo de transferencia. Debe ser o bien FTP_ASCII o FTP_BINARY.

	 * @return $res Devuelve TRUE si todo se llevó a cabo correctamente, FALSE en caso de fallo.
	 */
	function agregarArchivo ($id_con, $archivo, $apuntador, $tipo_archivo=FTP_ASCII){

		return @ftp_fput($id_con, $archivo, $apuntador, FTP_BINARY);
	}

	/**
	 * Obtener un archivo del servidor de FTP
	 * MODO DE USO:
	 *			  // ruta al archivo remoto
	 *				 $archivo_remoto = 'algun_archivo.txt';
	 *				 $archivo_local = 'archivo_local.txt';
	 *			  // abrir algún archivo para escritura
	 *                $gestor = fopen($archivo_local, 'w');
	 * @param $$id_con=identificador de la conexion FTP
	 * @param $gestor=Un apuntador de archivo abierto en el que se almacenan los datos => Archivo Local
	 * @param $archivo_remoto=La ruta del archivo remoto.
	 * @param $tipo_archivo=El modo de transferencia. Debe ser o bien FTP_ASCII o FTP_BINARY.
	 * @param $pos=La posición desde la cual se empieza a descargar el archivo remoto.

	 * @return $res Devuelve TRUE si todo se llevó a cabo correctamente, FALSE en caso de fallo.
	 */
	function obtenerArchivo ($id_con, $gestor, $archivo_remoto, $tipo_archivo=FTP_ASCII,$pos=0){

		return @ftp_fget($id_con, $gestor, $archivo_remoto, FTP_BINARY, 0);
	}

	/**
	 * Obtener un lisato de archivos y directorios a partir de un directorio
	 *
	 * @param $$id_con=identificador de la conexion FTP
	 * @param $directorio=Cual es el directorio que quiero listar
	 *
	 * @return $rawlist devuelve la lista de directorios y archivos con informacion de ellos
	 */
	function listarArchivos($id_con,$directorio) {
		$ftp_rawlist = ftp_rawlist($id_con, $directorio);
		if(!is_array($ftp_rawlist)) return;
		$rawlist = array();
		if(empty($ftp_rawlist)) return $rawlist;

		$partes = preg_split("/[\s]+/", $ftp_rawlist[0], 9);
		
		if(sizeof($partes)==4) $res = "Windows_NT";
		else $res = "UNIX";
		
		if($res=="UNIX") {
			foreach ($ftp_rawlist as $v) {
				$info = array();
				$vinfo = preg_split("/[\s]+/", $v, 9);
				if ($vinfo[0] !== "total") {
					$info['chmod'] = $vinfo[0];
					$info['num'] = $vinfo[1];
					$info['owner'] = $vinfo[2];
					$info['group'] = $vinfo[3];
					$info['size'] = $vinfo[4];
					$info['month'] = $vinfo[5];
					$info['day'] = $vinfo[6];
					$info['time'] = $vinfo[7];
					$info['name'] = $vinfo[8];
					if($info['chmod']{0} == "d") {
						$info['isDir'] = 1;
					}
					else {
						$info['isDir'] = 0;
					}
					$rawlist[$info['name']] = $info;

				}
			}
		}
	 if($res == "Windows_NT") {
	 	$i=0;
	 	foreach ($ftp_rawlist as $current) {
	 		ereg("([0-9]{2})-([0-9]{2})-([0-9]{2}) +([0-9]{2}):([0-9]{2})(AM|PM) +([0-9]+|<DIR>) +(.+)",$current,$split);
	 		if (is_array($split)) {
	 			if ($split[3]<70) { $split[3]+=2000; } else { $split[3]+=1900; } // 4digit year fix
	 			$parsed[$i]['isDir'] = ($split[7]=="<DIR>")?"1":"0";
	 			$parsed[$i]['size'] = $split[7];
	 			$parsed[$i]['month'] = $split[1];
	 			$parsed[$i]['day'] = $split[2];
	 			$parsed[$i]['time'] = $split[3];
	 			$parsed[$i]['name'] = $split[8];
	 			$rawlist[$parsed[$i]['name']] = $parsed[$i];
	 			$i++;
	 		}
	 	}
	 }
	 return $rawlist;
	}

	/**
	 * Lista solo el directorio pasado
	 *
	 * @param $$id_con=identificador de la conexion FTP
	 * @param $carpeta=Cual es la carpeta que quiero listar
	 * @param $expresionFiltradoArchivos= me diece una exprecion regular para filtrar archivos
	 *
	 * @return $archivos devuelve la lista de archivos que cumplen con el filtrado
	 */

	function listarNORecursivamente($id_con,$carpeta) {
		$resultados=$this->listarArchivos($id_con,$carpeta);
		if (!(empty($resultados))){
			foreach($resultados as $resultado) {
				if(!($resultado['isDir'])) {
					$archivos[] = array('nombrePath'=>$carpeta,
	     	 							'tamanio'=>$resultado['size'],
	     	 							'nombreFile'=>$resultado['name'],
	     	 							'info'=>$resultado);
				}
			}
			return $archivos;
		}
		else
		$archivos=$resultados;
	}

	/**
	 * Lista Recursivamente todos los directorio subdirectorios y archivos
	 *
	 * @param $$id_con=identificador de la conexion FTP
	 * @param $carpeta=Cual es la carpeta que quiero listar
	 * @param $expresionFiltradoArchivos= me diece una exprecion regular para filtrar archivos
	 *
	 * @return $archivos devuelve la lista de archivos que cumplen con el filtrado
	 */

	function listarRecursivamente($id_con,$carpeta,&$archivos) {
		$resultados=$this->listarArchivos($id_con,$carpeta);
		if (!is_array($archivos)) $archivos = array();
		if (!(empty($resultados))){
			foreach($resultados as $resultado) {
				if($resultado['isDir']) {
					$this->listarRecursivamente($id_con,$carpeta."/".$resultado['name'],$archivos);
				}
				else {
					$archivos[] = array('nombrePath'=>$carpeta,
	     	 							'tamanio'=>$resultado['size'],
	     	 							'nombreFile'=>$resultado['name'],
	     	 							'info'=>$resultado);
				}
			}
		}
	}

	/**
	 * Cierra la conexion de FTP
	 * @param $$id_con=identificador de la conexion FTP

	 * @return $res Devuelve TRUE si todo se llevó a cabo correctamente, FALSE en caso de fallo.
	 */
	function cerrarConexion (&$id_con){
		return @ftp_close($id_con);
	}

	/**
	 * Cambia de Directorio en el Servidor
	 * @param $id_con=identificador de la conexion FTP
	 * @param $archivo=nombre del archivo remoto qcon el que lo llamaremos
	 *
	 * @return $res Devuelve TRUE si todo se llevó a cabo correctamente, FALSE en caso de fallo.
	 */
	function cambiarDirectorio($id_con,$directorio){
		return @ftp_chdir($id_con,$directorio);
	}

	/**
	 * indica en que directorio estoy ubicado
	 * @param $id_con=identificador de la conexion FTP
	 *
	 * @return $res Devuelve el Directorio Actual si todo se llevó a cabo correctamente, FALSE en caso de fallo.
	 */
	function directorioActual($id_con){

		return @ftp_pwd($id_con);
	}

	function archivoExiste($id_con,$archivo) {
		$tamanio = @ftp_size($id_con,$archivo);
		if($tamanio>=0) return TRUE_;
		else return FALSE_;
	}



	function conectado(&$id_con) {
		if(empty($id_con)) return false;
		$res = ftp_rawlist($id_con,".");
		if(is_array($res)) return true;
		$id_con = 0;
		return false;
	}

	/**
	 * Borra el Archivo del Servidor FTP
	 * @param $id_con=identificador de la conexion FTP
	 * @param $$nombreArchivoPath=nombre del archivo remoto con el PATH completo
	 *
	 * @return $res Devuelve TRUE si todo se llevó a cabo correctamente, FALSE en caso de fallo.
	 */
	function borrarArchivo($id_con,$nombreArchivoPath){

		return ftp_delete($id_con,$nombreArchivoPath);
	}
}

?>