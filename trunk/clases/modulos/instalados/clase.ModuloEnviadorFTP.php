<?php
/**
 * @date 21/05/2008
 * @version 1.0
 * @author glerendegui
 * 
 * Esta clase contiene los métodos que invocan por dentro los enviador. Está la lógica del recoletor
 * */

include_once(BASE_DIR."clases/modulos/clase.Enviador.php");
include_once(BASE_DIR."clases/dao/dao.EnviadorFtp.php");
include_once(BASE_DIR."clases/dao/dao.DestinoFtp.php");
include_once(BASE_DIR."clases/negocio/clase.EnviadorFtp.php");
include_once(BASE_DIR."clases/negocio/clase.Actividad.php");
include_once(BASE_DIR."clases/util/clase.FTPDriver.php");
include_once(BASE_DIR."clases/negocio/clase.Central.php");



class ModuloEnviadorFTP extends Enviador {
	var $enviadorFTP;
	var $destinosFTP;
	var $actividad;
	var $actividadEnvio;
	var $ftpDriver = 0;
	var $conexionActiva = 0;
	
	function preparar($actividadEnvio) {
	    //Se crea el enviadorFTP
		$this->enviadorFTP = $this->obtenerEnviadorFTP($actividadEnvio);
		//Se crea una array con los $destinosFTP
		$this->destinosFTP  = $this->obtenerDestinosFTP($this->enviadorFTP);
		// Se obtiene un objeto Actividad
		
		$this->actividadEnvio = $actividadEnvio;
		$this->actividad = new Actividad($actividadEnvio->id_actividad);		
		
		for ($i=0; $i < count($this->destinosFTP); $i++){
			$this->destinosFTP[$i]->ubicacion_archivos=$this->obtenerDierectorioRaiz($this->destinosFTP[$i],$this->enviadorFTP);
   	    }
		
	}
	
	function reconectar() {
		static $destinoActual = 0;
		static $intentosParaDestinoActual = 0;
		if($this->ftpDriver == 0) $this->ftpDriver = new FTPDriver();
		
		// Se llamó mal, porque ya está conectada
		if($this->ftpDriver->conectado($this->conexionActiva)) return true;
		// Me pase de destinos
		if($destinoActual>=sizeof($this->destinosFTP)) return false;
		while($intentosParaDestinoActual < $this->destinosFTP[$destinoActual]->intentos_conexion) {
			// Intento para la misma
			$destino = $this->destinosFTP[$destinoActual];
			registrarLog(BATCH_LOG_DEBUG,"Conectando a ".$destino->direccion_ip." intento ".$intentosParaDestinoActual);
			$this->conexionActiva = $this->ftpDriver->conexion($destino->direccion_ip,$destino->usuario,$destino->contrasenia,$destino->modo_pasivo,$destino->puerto,$destino->timeout);
			if($this->ftpDriver->conectado($this->conexionActiva)) {
				registrarLog(BATCH_LOG_DEBUG,"Conectado, cambiando a la carpeta ".$destino->ubicacion_archivos);
				// Voy por el cambio de carpeta
				if($this->ftpDriver->cambiarDirectorio($this->conexionActiva,$destino->ubicacion_archivos)) {
					// Todo Ok 
					registrarLog(BATCH_LOG_DEBUG,"Entro en la carpeta");
					return true;
				}
				else {
					// La carpeta está mal, capaz se desconecto y capaz no existe la carpeta
					if($this->ftpDriver->conectado($this->conexionActiva)) {
						// Todavia conectado - la carpeta está mal
						registrarLog(BATCH_LOG_ERROR,"No se encontró la carpeta ".$destino->ubicacion_archivos);
						$intentosParaDestinoActual = $destino->intentos_conexion + 1;
						$this->ftpDriver->cerrarConexion($this->conexionActiva);
						$this->conexionActiva = 0;
						continue; // Continuo para que siga del while con otro origen
					}
					else {
						// Se desconecto
						$intentosParaDestinoActual++;
						continue;
					}
				}
			}
			$intentosParaDestinoActual++;
		}
		// Salio del while sin conectar, pruebo con otra
		$destinoActual++;
		$intentosParaDestinoActual = 0;
		registrarLog(BATCH_LOG_DEBUG,"Cambiando al siguiente destino ".$destinoActual);
		return $this->reconectar();
	
	}	
	
	function enviarArchivos() {
		$tareaTerminada = false;
		
		$archivos = $this->getArchivosAEnviar();
		$archivoActual = 0;
		while ( ($this->reconectar()) && (!$tareaTerminada) ) {
			// Trato de subir todos los archivos
			
			// Si ya bajo todos
			if($archivoActual >= sizeof($archivos)) {
				$tareaTerminada = true;
				break;
			}
			
			// Trato de subir un archivo
			$error = "";
			$nombreFinal = "";
			$mando = $this->enviarArchivo($archivos[$archivoActual],$this->enviadorFTP,$error,$nombreFinal);
			if($mando) {
				// Subio un archivo bien
				$this->registrarArchivoOk($archivoActual,$nombreFinal);
			}
			else {
				// No lo subio bien
				$this->registrarArchivoError($archivoActual,$nombreFinal,$error);
			}
			$archivoActual++;
		}
		if(!$tareaTerminada) {
        	// No los pudo enviar
            for($i=$archivoActual;$i<sizeof($archivos);$i++) {
            	$this->registrarArchivoError($i,"","No se pudo conectar");
            }
        }
		
	}
	
	
	function enviarArchivo($archivo,$enviadorFtp,&$error,&$nombreRemoto) {
		     //Se aplica el RENOMBRADO
		     $nombreRemoto = $this->renombrarArchivoRemoto($archivo,$enviadorFtp);
		     $nombreLocal = $archivo['ubicacionTemporal']."/".$archivo['archivoTemporal'];
		     $tareaTerminada = false;

		     //Abrir el el Archivo Local
		     $apuntador = fopen($nombreLocal, 'r');
		     if(!$apuntador) {
		     	$error = "Imposible abrir archivo necesario para la subida $nombreLocal";
		     	return false;
		     }		     
		     
			 while ( ($this->reconectar()) && (!$tareaTerminada) ) {
			 	if( $this->ftpDriver->archivoExiste($this->conexionActiva,$nombreRemoto) ) {
			 		// El archivo ya existe
			 		$error = "Archivo $nombreRemoto ya existente en el servidor";
			 		fclose($apuntador);
			 		return false;
			 	}
			 	else {
			 		// No pudo resolver el tamanio
			 		// Pq se desconecto
			 		if(!$this->ftpDriver->conectado($this->conexionActiva)) {
			 			continue;
			 		}

			 		// Pq no hay archivo, entonces trato de subir
			 		$res = $this->ftpDriver->agregarArchivo($this->conexionActiva,$nombreRemoto,$apuntador);
			 		if($res) {
			 			// Envio el archivo
			 			fclose($apuntador);
			 			return true;
			 		}
			 		else {
			 			if(!$this->ftpDriver->conectado($this->conexionActiva)) {
				 			// Fallo pq se desconecto
			 				continue;
			 			}
			 			else {
			 				// Por otra causa
			 				$error = "Error no determinado";
			 				fclose($apuntador);
			 				return false;
			 			}
			 		}
			 	}
			 }
			$error = "No se pudo enviar";
			return false;
		      
		     
	}
	
	/*** Obtiene el Enviador FTP que le corresponde a la Actividad Envio solo 1 devuelve
	 *   @params $ActividadEnvio =  es el objeto ActividadEnviador
	 *   @return array de objetos EnviadorFTP que siempre sera 1 solo
    */
	function obtenerEnviadorFTP($ActividadEnvio){

		//Se instacia al DAO de EnviadorFTP para que me devuelva el 
		$enviadorFTPDAO=new EnviadorFTPDAO();

		//EnviadorFTP a partir de una actividad envio
		return $enviadorFTPDAO->getByIdActividadEnvio($ActividadEnvio->id);

	}
	
	function obtenerDestinosFTP($enviadorFTP){

		//Se instacia al DAO de DestinoFtpDAO para que me devuelva el DAO Destino
		$destinoFtpDAO=new DestinoFtpDAO();

		//DestinoFTP (ordenados por nro_orden)a partir de un EnviadorFTP
		return $destinoFtpDAO->getByIdEnviadorFtp($enviadorFTP->id);

	}

	
    function obtenerDierectorioRaiz($destinoFtp,$enviadorFTP){
    	     //TODO VER QUE DEVUELVE SI EL CAMPO ES NULL
		     //Se obtener a partir de la mascara el nombre del directorio Remoto
		     if(!(empty($destinoFtp->ubicacion_archivos))){
		     	$central=new Central($this->actividad->id_central);
		     	$fecha=new Fecha();
		     	$fecha->loadFromNow();
		     	//Le doy el Formato correcto a la Fecha
		     	$fecha->dateToString();
		        $directorioRemoto=trim(renombradoArchivo('',$destinoFtp->ubicacion_archivos,$central->codigo,'',$fecha)); 
		        return $directorioRemoto;
		     }
		     else
		         return './';
    }	

    function renombrarArchivoRemoto($archivo,$enviadorFtp){
    		 $archivoRenombradoLocal = $archivo['archivoTemporal'];
		     //Se renombra el archivo para guardarlo LOCALMENTE
		     if (!(empty($enviadorFtp->formato_renombrado))){
		     	$central=new Central($this->actividad->id_central);
		     	// TODO: Usar fecha del nucleo
		     	$fecha=new Fecha();
		     	$fecha->loadFromNow();
		     	//Le doy el Formato correcto a la Fecha
		     	$fecha->dateToString();
		     	$archivoRenombradoLocal=renombradoArchivo($archivoRenombradoLocal,$enviadorFtp->formato_renombrado,$central->codigo,$archivo['idTarea'],$fecha); 
		     }
		     //Si no hay Mascara para el Renombrado se usa el nombre que tenia en el Origen
  		     return $archivoRenombradoLocal;
    }
	
}

?>