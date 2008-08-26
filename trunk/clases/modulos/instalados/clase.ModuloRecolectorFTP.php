<?php
// Batch
include_once(BASE_DIR."clases/modulos/clase.Recolector.php");

//Clases
//include_once(BASE_DIR."clases/general.Central.php"); // Mal incluido. Donde esta general.Central.php?
include_once(BASE_DIR."clases/dao/dao.RecolectorFtp.php");
include_once(BASE_DIR."clases/dao/dao.OrigenFtp.php");
include_once(BASE_DIR."clases/negocio/clase.Central.php");
include_once(BASE_DIR."clases/util/clase.FTPDriver.php");


class ModuloRecolectorFTP extends Recolector {
	var $recolectorFtp;
	var $origenesFtp;
	var $actividad;
	var $conexionActiva = 0;
	var $ftpDriver = 0;
	
	function preparar($actividad) {
		// Se obtiene la instancia del recolector FTP 
		$this->recolectorFtp = $this->obtenerRecolectorFtp($actividad->id_central);
		// Se obtiene un array de los Origenes del Recolector FTP 
		$this->origenesFtp = $this->obtenerOrigenesFtp($this->recolectorFtp->id);
		// Se obtiene un objeto Actividad
		$this->actividad = $actividad;
		//Piso el atributo ubicacion_archivos que es una mascara por el valor de la mascara ya transformado
   	    for ($i=0; $i < count($this->origenesFtp); $i++){
			$this->origenesFtp[$i]->ubicacion_archivos=$this->obtenerDierectorioRaiz($this->origenesFtp[$i],$this->recolectorFtp);
   	    }
	}
	
	function reconectar() {
		static $origenActual = 0;
		static $intentosParaOrigenActual = 0;
		if($this->ftpDriver == 0) $this->ftpDriver = new FTPDriver();
		
		// Se llamó mal, porque ya está conectada
		if($this->ftpDriver->conectado($this->conexionActiva)) return true;
		
		// Me pase de origenes
		if($origenActual>=sizeof($this->origenesFtp)) return false;

		while($intentosParaOrigenActual < $this->origenesFtp[$origenActual]->intentos_conexion) {
			// Intento para la misma
			$origen = $this->origenesFtp[$origenActual];
			registrarLog(BATCH_LOG_DEBUG,"Conectando a ".$origen->direccion_ip." intento ".$intentosParaOrigenActual);
			$this->conexionActiva = $this->ftpDriver->conexion($origen->direccion_ip,$origen->usuario,$origen->contrasenia,$origen->modo_pasivo,$origen->puerto,$origen->timeout);
			if($this->ftpDriver->conectado($this->conexionActiva)) {
				registrarLog(BATCH_LOG_DEBUG,"Conectado, cambiando a la carpeta ".$origen->ubicacion_archivos);
				// Voy por el cambio de carpeta
				if($this->ftpDriver->cambiarDirectorio($this->conexionActiva,$origen->ubicacion_archivos)) {
					// Todo Ok 
					registrarLog(BATCH_LOG_DEBUG,"Entro en la carpeta");
					return true;
				}
				else {
					// La carpeta está mal, capaz se desconecto y capaz no existe la carpeta
					if($this->ftpDriver->conectado($this->conexionActiva)) {
						// Todavia conectado - la carpeta está mal
						registrarLog(BATCH_LOG_ERROR,"No se encontró la carpeta ".$origen->ubicacion_archivos);
						$intentosParaOrigenActual = $origen->intentos_conexion + 1;
						$this->ftpDriver->cerrarConexion($this->conexionActiva);
						$this->conexionActiva = 0;
						continue; // Continuo para que siga del while con otro origen
					}
					else {
						// Se desconecto
						$intentosParaOrigenActual++;
						continue;
					}
				}
			}
			$intentosParaOrigenActual++;
		}
		// Salio del while sin conectar, pruebo con otra
		$origenActual++;
		$intentosParaOrigenActual = 0;
		registrarLog(BATCH_LOG_DEBUG,"Cambiando al siguiente origen ".$origenActual);
		return $this->reconectar();
	
	}
	
	
	function recolectarActividad($actividadEnvio,$archivosExclusivos=0) {
		$recolectorFtp = $this->recolectorFtp;
		$origenesFtp = $this->origenesFtp;
		$actividad = $this->actividad;
		
		$tareaTerminada = false;
		$archivos = array();
		
		// Busco listar los archivos, si no me los pasaron ya como parametro
		if(!$archivosExclusivos ) {
			$archivos = $this->obtenerArchivos();
			if(!$archivos) {
				// No pudo listar nunca, salio todo mal
				registrarLog(BATCH_LOG_ERROR,"Imposible descargar los archivos");
				return false;
			}
			
			$archivosTipos=$this->filtrarMD5_SHA($archivos,$this->actividad);
			
			//Registros los Archivos que debo Obtener			
	        foreach($archivosTipos['obtener'] as $archivo) 
	        	$this->registrarArchivo($archivo['nombrePath'],$archivo['nombreFile'],$archivo['tamanio']);
	        	
		}
		else {
			$this->archivosARecolectar = $archivosExclusivos;
		}
        //Obtengo todos los archivos a procesar y OBTENER
        $archivos = $this->getArchivosARecolectar();
		
        $archivoActual = 0;
		while ( ( $this->reconectar() ) && (!$tareaTerminada) ) {
			// Trato de trabajar todos los archivos
			
			// Si ya bajo todos
			if($archivoActual >= sizeof($archivos)) {
				$tareaTerminada = true;
				break;
			}
			
			// Trato de bajar un archivo
			$error = "";
			$nombreFinal = "";
			$trajo = $this->traerArchivo($archivos[$archivoActual],$this->recolectorFtp,$error,$this->actividad,$nombreFinal);
			if($trajo) {
				// Trajo un archivo bien
				$this->registrarArchivoOk($archivoActual,$nombreFinal);
				if ($recolectorFtp->borrar_archivos){
					//Borro el Archivo que se encuentra en el Servidor FTP
					if (!($this->ftpDriver->borrarArchivo($this->conexionActiva,$archivos[$archivoActual]['ubicacionOriginal']."/".$archivos[$archivoActual]['archivoOriginal'])));
                    	registrarLog(BATCH_LOG_ERROR,"No se ha podido borrar el Archivo Origen del servidor FTP: ".$archivos[$archivoActual]['ubicacionOriginal']."/".$archivos[$archivoActual]['archivoOriginal']);
				}
			}
			else {
				// No lo trajo bien
				$this->registrarArchivoError($archivoActual,$error);
			}
			$archivoActual++;
		}
		
		// Capaz salio y no termino de procesar los archivos
		if(!$tareaTerminada) for($i=$archivoActual;$i<sizeof($archivo);$i++) {
			$this->registrarArchivoError($i,"Se alcanzó el límite de conexiones posibles");
		}

	}//CIERRO LA CLASE
		
	/**
	 * Obtiene el Archivo
	 *
	 * @param String $idCentral
	 * @return RecolectorFtp
	 */
	function traerArchivo($archivo,$recolectorFtp,&$error,$actividad,&$archivoLocal) {

		     //Se aplica el RENOMBRADO
		     $archivoLocal = $this->renombrarArchivoLocal($archivo,$recolectorFtp,$actividad);
            
		     //Genero el PATH completo de Archivo Local en donde va a estar
		     $pathLocal=$archivo['ubicacionTemporal'];
		         
		     //Le agrego el PATH al archivo, para que lo tenga completo
		     $archivoLocalPath = $pathLocal.'/'.$archivoLocal;
		     		     
			 //Abrir el Archivo Local
			 if (file_exists($archivoLocalPath)) {
			 	$error = "Archivo ya existente $archivoLocalPath";
			 	return false;
			 }
	     
	         //Abrir el el Archivo Local
		     $apuntador = fopen($archivoLocalPath, 'w');
		     if(!$apuntador) {
		     	$error = "Imposible abrir archivo necesario para la descarga $archivoLocalPath";
		     	return false;
		     }
			 
		     $tareaTerminada = false;
		     while( ( $this->reconectar() ) && ( !$tareaTerminada ) ) {
		     	if( $this->ftpDriver->obtenerArchivo($this->conexionActiva,$apuntador,$archivo['ubicacionOriginal']."/".$archivo['archivoOriginal']) ) {
		     		// Trajo bien el archivo
		     		$tareaTerminada = true;
		     		break;
		     	}
		     	else {
		     		// No trajo bien el archivo
		     		if(!$this->ftpDriver->conectado($this->conexionActiva)) {
		     			// Se desconecto, pruebo conectar
		     			continue;
		     		}
		     		else {
		     			// Fallo por otro motivo
		     			$error = "Imposible descargar archivo ".$archivo['ubicacionOriginal']."/".$archivo['archivoOriginal'];
		     			fclose($apuntador);
		     			return false;
		     		}
		     	}
		     }
		     
		     $error = "";
		     //TODO Falta las Validacion de SHA y MD5 y el Tamanio
		     
		     if(!$this->aplicarValidaciones($recolectorFtp,$archivo,$archivoLocalPath,$conexion,$id_con,$error)) {
		     	$error = "Archivo ".$archivo['archivoOriginal']." no cumplió con las validaciones";
		     	fclose($apuntador);
		     	return false;
		     }
		     
		     //Cierro el archivo
		     fclose($apuntador);
		     return $tareaTerminada;
	}
		
	
		
	/**
	 * Devuelve una instancia de une recolector FTP a partir de un id de central
	 *
	 * @param String $idCentral
	 * @return RecolectorFtp
	 */
	function obtenerRecolectorFtp($idCentral) {
		$daoRecolectorFtp = new RecolectorFtpDAO();
		$recolectorFtp = $daoRecolectorFtp->getByIdCentral($idCentral);
		return $recolectorFtp;
	}
	
	
	/**
	 * Devuelve la lista de origenes FTP a partir del ir del recolector FTP
	 *
	 * @param String $idRecolectorFtp
	 * @return Array
	 */
	function obtenerOrigenesFtp($idRecolectorFtp) {
		$daoOrigenFtp = new OrigenFtpDAO();
		$origenes = $daoOrigenFtp->getByIdRecolectorFtp($idRecolectorFtp);
		return $origenes;
	}
	
	function obtenerArchivos(){
		 $archivos = array();
		 $tareaTerminada = false;
		 while(($this->reconectar())&&(!$tareaTerminada)) {
			 //Me fijo si esta seteado la busqueda recursiva
	      	 if ($this->recolectorFtp->busqueda_recursiva){
	      	 	 $this->ftpDriver->listarRecursivamente($this->conexionActiva,'.',&$archivos);
	         }
	         else{
	         	 $archivos=$this->ftpDriver->listarNORecursivamente($this->conexionActiva,'.');
	         }
      	 	 if(!($this->ftpDriver->conectado($this->conexionActiva))) {
      	 	 	// Se desconecto listando, vamos de nuevo
      	 	 	continue;
      	 	 }
      	 	 else {
      	 	 	// Listo, termino ok
      	 	 	return $archivos;
      	 	 }
		 }
         return false;
    }
	
	function aplicarValidaciones($recolectorFTP,$datosArchivoRemoto,$nombreArchivoLocal,$conexion,$id_con,&$error){

		    //Inicializo por defecto en true
	 	    $condiconSHA=true;
	 	    $condicionTam=true;
	 	    //TODO HACER PARA LA ITERACION 2 LO DE SHA
			//if ($recolectorFTP->firma_SHA){
		    //    $condiconSHA=validarSHA1($nombreArchivoLocal->formato_renombrado,$nombreArchivoRemoto); 
		    //}
		    if ($recolectorFTP->tamanio){
		        $condicionTam=tamanoArchivos($datosArchivoRemoto['tamanio'],$nombreArchivoLocal);
		        return $condicionTam;
		    }
            return true; 
	}
		     
	/**
	 * Devuelve el nombre el archivo que tendra localmente sin el PATH completo solo el nombre del Archivo
	 *
	 * @param String $nombreArchivoRemoto= nombre del archivo Remoto sin el PATH solo el nombre del archivo
 	 * @param objeto $recolectorFTP= el objeto recolectorFTP
	 * @return String $archivoRenombradoLocal=nombre del archivo lLocal renombrado o no sin el PATH completo
	 */
	
    function renombrarArchivoLocal($archivo,$recolectorFtp,$actividad){
    	
    		 $archivoRenombradoLocal = $archivo['archivoOriginal'];
		     //Se renombra el archivo para guardarlo LOCALMENTE
		     if (!(empty($actividad->formato_renombrado_recoleccion))){
		     	$central=new Central($recolectorFtp->id_central);
		     	$fecha=new Fecha();
		     	$fecha->loadFromNow();
		     	//Le doy el Formato correcto a la Fecha
		     	$fecha->dateToString();
		     	$archivoRenombradoLocal=renombradoArchivo($archivoRenombradoLocal,$actividad->formato_renombrado_recoleccion,$central->codigo,$archivo['idTarea'],$fecha); 
		     }
		     //Si no hay Mascara para el Renombrado se usa el nombre que tenia en el Origen
  		     return $archivoRenombradoLocal;
    }
    
	/**
	 * Devuelve el nombre el directorio raiz del origen FTP, del cual voy a recorrer
	 *
	 * @param String $nombreArchivoRemoto= nombre del archivo Remoto sin el PATH solo el nombre del archivo
 	 * @param objeto $recolectorFTP= el objeto recolectorFTP
	 * @return String $archivoRenombradoLocal=nombre del archivo lLocal renombrado o no sin el PATH completo
	 */
	
    function obtenerDierectorioRaiz($origenFtp,$recolectorFTP){
    	
    	     //TODO VER QUE DEVUELVE SI EL CAMPO ES NULL
		     //Se obtener a partir de la mascara el nombre del directorio Remoto
		     if(!(empty($origenFtp->ubicacion_archivos))){
		     	$central=new Central($recolectorFTP->id_central);
		     	$fecha=new Fecha();
		     	$fecha->loadFromNow();
		     	//Le doy el Formato correcto a la Fecha
		     	$fecha->dateToString();
		        $directorioRemoto=trim(renombradoArchivo('',$origenFtp->ubicacion_archivos,$central->codigo,'',$fecha)); 
		        return $directorioRemoto;
		     }
		     else
		         return './';
    }

    /**
	 * Devuelve el un array asociativo con el conjunto de archivos MD5, SHA y los obtener (que debo obtener) y basura
	 *
	 * @param array $archivos= conjunto de archivos que se obtuvo en FTPLIST
	 * @return array $$archivosTipos=los tres tipos de conjuntos de archivos tanto MD5, SHA y Comunes
	 */
       
    function filtrarMD5_SHA($archivos,$actividad){
 
    	$archivosMD5=array();
    	$archivosSHA=array();
    	$archivosObtener=array();
    	for($i=0; $i<count($archivos); $i++){
    		if (ereg('.*\.MD5$',$archivos[$i]['nombreFile']))
    			$archivosMD5[]=$archivos[$i];
    		else
	    		if (ereg('.*\.SHA$',$archivos[$i]['nombreFile']))
	    		    $archivosSHA[]=$archivos[$i];
	    		else
	    			if ((empty($actividad->filtro_archivos_recoleccion)) || (ereg("$actividad->filtro_archivos_recoleccion",$archivos[$i]['nombreFile'])))
	    		    $archivosObtener[]=$archivos[$i];
		    		else
		    		    $archivosBasura[]=$archivos[$i];
    	}
    	$archivosTipos['MD5']=$archivosMD5;
    	$archivosTipos['SHA']=$archivosSHA;
    	$archivosTipos['obtener']=$archivosObtener;
    	$archivosTipos['basura']=$archivosBasura;
    	return $archivosTipos;
    }
}

?>