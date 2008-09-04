<? 
include_once BASE_DIR ."clases/listados/clase.ListadoUsuarios.php";
include_once BASE_DIR ."clases/listados/clase.ListadoPlanificacion.php";
include_once BASE_DIR ."clases/listados/clase.ListadoDispositivos.php";
include_once BASE_DIR ."clases/listados/clase.ListadoGuardias.php";
include_once BASE_DIR ."clases/listados/clase.ListadoRondasRealizadas.php";
include_once BASE_DIR ."clases/listados/clase.ListadoAlarmas.php";   

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
