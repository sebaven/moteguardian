<?
include_once BASE_DIR ."clases/negocio/clase.Log.php";

/// Clase con m�todos ESTATICOS para la registraci�n de acciones en el Log
class Logger
{
	/// Registra en el log la acci�n especificada
	/// @public
	/// @param $message (int) c�digo de la acci�n (con un patr�n ##param## que ser� reemplazado
	/// con el string especificado en $parametro
	/// @param $paramtro (string) texto adicional que se se reemplazar� en el patr�n ##param## que contenga la
	/// la descripci�n de la acci�n
	/// @return (bool) true si se grab� correctamente, false en caso contrario
	function register($accion, $parametro = '')
	{
		$log = new Log();
		$log->id_accion = $accion;
		$log->fecha = date("Y/m/d");
		$log->hora = date("H:i:s");
		$log->parametro = ($parametro) ? $parametro : NULL;

		$log->id_usuario = RegistryHelper::getIdUsuario();

		return $log->insert();
	}
	
	/**
	 * Esta funcion guarda en el log para el sistema de auditoria
	 * @access public
	 * @author: fzalazar
	 * @param $accion: id de la accion
	 * @param $parametro: descripcion de la accion (opcional)
	 * @param $torneo: id del torneo (opcional)
	 * @param $rueda: rueda (opcional)
	 * @param $fecha: numero de fecha (opcional)
	 * @param $partido: partido (opcional)
	 * @return true si pudo insertar en el log, false en caso contrario
	 * @modificacion: dabiricha (agregado de parametros opcionales: torneo, rueda, fecha y partido)
	 */
	function registerPartido($accion, $parametro = '', $torneo = 0, $rueda = 0, $fecha = 0, $partido = 0){
		$log = new log();
		$log->id_accion = $accion;
		$log->fecha = date("Y/m/d");
		$log->hora = date("H:i:s");
		$log->parametro = ($parametro) ? $parametro : NULL;
		$log->torneo = $torneo;
		$log->rueda = $rueda;
		$log->numero_fecha = $fecha;
		$log->partido = $partido;
		
		$log->id_usuario = RegistryHelper::getIdUsuario();
		
		return $log->insert();
	}
}
?>