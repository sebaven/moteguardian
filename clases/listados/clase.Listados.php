<? 
include_once BASE_DIR ."clases/listados/clase.ListadoAuditoria.php";
include_once BASE_DIR ."clases/listados/clase.ListadoUsuarios.php";
include_once BASE_DIR ."clases/listados/clase.ListadoPlanificacion.php";
include_once BASE_DIR ."clases/listados/clase.ListadoCentrales.php";
include_once BASE_DIR ."clases/listados/clase.ListadoActividad.php";
include_once BASE_DIR ."clases/listados/clase.ListadoPlantillas.php";
include_once BASE_DIR ."clases/listados/clase.ListadoTareas.php";
include_once BASE_DIR ."clases/listados/clase.ListadoPlanificacionEnvio.php";
include_once BASE_DIR ."clases/listados/clase.ListadoTecnologiaCentral.php";
include_once BASE_DIR ."clases/listados/clase.ListadoCentralesAsignadasARecoleccion.php";
include_once BASE_DIR ."clases/listados/clase.ListadoCentralesAsignadasARecoleccionManual.php";
include_once BASE_DIR ."clases/listados/clase.ListadoCentralesParaSeleccionar.php";
include_once BASE_DIR ."clases/listados/clase.ListadoHosts.php";
include_once BASE_DIR ."clases/listados/clase.ListadoRecolecciones.php";
include_once BASE_DIR ."clases/listados/clase.ListadoEnvios.php";
include_once BASE_DIR ."clases/listados/clase.ListadoFicherosAsignadosAEnvioManual.php";
include_once BASE_DIR ."clases/listados/clase.ListadoFicherosParaSeleccionar.php";
include_once BASE_DIR ."clases/listados/clase.ListadoRecoleccionesConFallas.php";
include_once BASE_DIR ."clases/listados/clase.ListadoEnviosConFallas.php";

include_once BASE_DIR ."clases/listados/clase.ListadoEstIntentosRecoleccion.php";
include_once BASE_DIR ."clases/listados/clase.ListadoEstIntentosEnvio.php";

include_once BASE_DIR ."clases/listados/clase.ListadoEstTotalTickets.php";
include_once BASE_DIR ."clases/listados/clase.ListadoEstTotalFicheros.php";

include_once BASE_DIR ."clases/listados/clase.ListadoMonitoreoEnvioTiempoReal.php";
include_once BASE_DIR ."clases/listados/clase.ListadoMonitoreoRecoleccionTiempoReal.php";

class Listados
{		
	/// Crea un objeto de la clase listado correspondiente, pasandole los par�metros especificados 
	/// @public
	/// @type (string) nombre de la clase listado correspondiente
	/// @array_config (array) contiene como clave cada uno de los par�metros que se le van a pasar al
	/// objeto creado
	/// @return referencia al objeto creado
	function create($type, $array_config = '')
	{		
		// Crear el objeto de la clase correspondiente al tipo
		eval('$listado = new ' . "$type" . '($array_config);');

		// Devolver una referencia al objeto
		return $listado;
	}	
}

?>
