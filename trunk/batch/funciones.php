<?
/**
 * @date 20/05/2008
 * @version 1.0
 * @author glerendegui
 */
include_once(BASE_DIR."comun/Fecha.php");

if(!defined("BASE_DIR_RELATIVO")) {
	die("ERR-CONF-Inclusión indebida de fichero funciones.php\n");
}

function finalizarPorError() {
	global $nombreArchivoLog;
	echo "El proceso finalizó inesperadamente - revise el log $nombreArchivoLog\n";
	registrarLog(BATCH_LOG_ERROR,"Aplicación finalizada abruptamente");
	die();
}

/* Si archivo origen se llama: “ewsd_archivo1“
 * En SRDF debe existir un proceso que renombre y trasfiera al Validador con este 
 * nombre:
 *		  “0926666_TZ.000345_080102_VAL“
 *				%C_TZ.%T_%d%m%a_VAL
 *
 * Es decir la lógica es: 
 * PPPPPPP+”Texto Fijo”+TTTTTT+”Texto Fijo”+AAMMDD (del Proceso) +”Texto Fijo”
 *
 * En donde:
 * PPPPPPP=”0926666”
 * Texto Fijo=”_TZ”
 * TTTTTT=”000345”
 * Texto Fijo=”_”
 * AAMMDD (del Proceso)=”080102”
 * Texto Fijo=”_VAL”
 */

function renombradoArchivo($nombre_original,$mascara_renombrado='%C_TZ.%T_%d%m%a_VAL',$codigo_centra,$numero_tarea,$fecha){

  		 $cadena=trim($mascara_renombrado);
		 $longitud = strlen($cadena);
		 if($longitud==0) return $nombre_original;
		 $ordenParametros = array();
		 $j=0;
		 $entroFijo=false;
		 $str_tarea = sprintf("%06d",$numero_tarea);
		 $numero_tarea = $str_tarea;
		 //Me armo un array con las campos en orden que tengo que mostrar	
		 for ($i=0;$i<$longitud;){
		 	 if($cadena{$i}=='%'){
		 	 	if ($entroFijo)
		 	 	    $j++;
           		@$ordenParametros[$j]=$cadena{$i+1};
           		$i=$i+2;
           		$j++;
 			    $entroFijo=false;
		 	 }
		 	 else{
  		 	 	 if (!(isset($ordenParametros[$j]))){
	 		 	 	   @$ordenParametros[$j]=$cadena{$i};
  		 	 	 }
	 	 	     else{
		 	 	     @$ordenParametros[$j].=$cadena{$i};
	 	 	     }
		 	 	 $i++;
		 	 	 $entroFijo=true;
		 	 }
 		 } 
 		 //Genero el nombre del ArchivoRemoto que tendra
 		 $nroOrden=0;
 		 $nombreRemoto='';
		 for($nroOrden;$nroOrden<count($ordenParametros);$nroOrden++){
			 switch ($ordenParametros[$nroOrden]){
			     case 'C':
			     case 'c':
			         $nombreRemoto.=$codigo_centra;
			         break;
			     case 'T':
			     case 't':
			         $nombreRemoto.=$numero_tarea;
			         break;
			     case 'A':
			         $nombreRemoto.=$fecha->yearToString(2);
			         break;
			     case 'a':
			         $nombreRemoto.=$fecha->yearToString(2);
			         break;
			     case 'M':
			         $nombreRemoto.=$fecha->monthToString();
			         break;
			     case 'm':
			         $nombreRemoto.=$fecha->monthToString();
			         break;
		         case 'D':
			         $nombreRemoto.=$fecha->dayToString();
			         break;
		         case 'd':
			         $nombreRemoto.=$fecha->dayToString();
			         break;
			         
			     default:
			         $nombreRemoto.=$ordenParametros[$nroOrden];
			 }
		 }
		 return $nombreRemoto;
}

/**
	* Valida que el valorSHARemoto y valorSHALocal sean iguales
	* Ejemplo de valores de hash '6cc76bddf29e934258e30fbf301ad8ec89cb7cc6'
	* @param $nombreArchivoLocal= nombre del archivo Local con el PATH completo o sea el archivo que esta en la Central
	* @param $$valorSHARemoto= es el valor de hash pero del archivo Remoto, que se obtiene del origen y no de la Central
	* @return false si no se puede abrir el archivo o no se puede calcular el tamaño del archivo
 	* @return CUMPLE_HASH si los dos hash son iguales
  	* @return ERROR_HASH si los dos hash son distintos
*/

function validarSHA1($nombreArchivoLocal, $valorSHARemoto){
	
	$apuntador = fopen($nombreArchivoLocal, 'r');
	$tamano=filesize($nombreArchivoLocal);

	//Si se pudo abrir el archivo 
	if (($apuntador!=false)&&($tamano!=false)){
		$contenido=fread($apuntador,$tamano);
		$valorSHALocal=sha1($contenido);

		//Comparo si los valores de hash son iguales
		if ("$valorSHALocal" == "$valorSHARemoto") {
			return 'CUMPLE_HASH';//Si los hash son iguales
		}
		else
		    return 'ERROR_HASH';//Si los hash son distintos     
	}
	else{
	 	return false;//Si el archivo no se pudo abrir o calcular su tamaño
	}
}

/**
	* Valida que el tamanio del archivo descargado sea igual al que se obtuvo remotamente
	* @param $tamanoArchivoRemoto= tamano del Archivo Remoto
	* @param $nombreArchivoLocal= nombre del Archivo Local con el PATH completo
	* @return 1 si el tamanio del archivo recibido es mayor al tamanio informado remotamente
	* @return -1 si el tamanio del archivo recibido es menor al tamanio informado remotamente
	* @return 0 si el tamanio del archivo recibido es igual al tamanio informado remotamente
*/

function tamanoArchivos($tamanoArchivoRemoto, $nombreArchivoLocal){
		$tamanoLocal=filesize($nombreArchivoLocal);
		//Si el tamanio del archivo recibido es mayor al informado remotamente, implica q el archivo se estaba generando
		//en el momento en que comenzó a descargarse
		if ($tamanoLocal > $tamanoArchivoRemoto)
			return 1;
		//Si el tamanio del archivo recibido es menor al informado remotamente, implica que el mismo no se recibió completo.	
		else if ($tamanoLocal < $tamanoArchivoRemoto)
			return -1;
		//Si los tamanios son iguales, el archivo se recibió correctamente.	
		else return 0;
}

function limpiarVectorIndicesSalteados($vector) {
	$resul = array();
	if(!is_array($vector)) {
		return $resul;
	}
	foreach($vector as $elemento) {
		$resul[] = $elemento;
	}
	return $resul;
}


?>
