<?
/**
 * Clase para la administración de los errores de PHP. Al crear un objeto de esta clase se modifica el 
 * handler de los errores, de manera tal que en lugar de ser visualizados en pantalla son logeados a 
 * un archivo en formato texto.
 * 
 * ----------------------------------------
 *      iOnix - PHP Framework - 2006
 * ----------------------------------------
 * Jorge Barbosa      <jmbarbosa@gmail.com>
 * Alejandro Molinari <amolinari@gmail.com>
 * ----------------------------------------
 * 
 * @version 1.1
 * @package common
 * @author jbarbosa
 */ 
class ErrorHandler
{
	/**
	 * Path del archivo donde se van a logear los errores
	 * @access public
	 * @var string
	 */ 
	var $filename = LOG_FILE;
	
	/**
	 * Indica si se logean o no los E_NOTICE y E_USER_NOTICE
	 * @access public
	 * @var boolean
	 */ 
	var $log_notices;
	
	/**
	 * Indica si se logean o no los E_WARNING y E_USER_WARNING
	 * @access public
	 * @var boolean
	 */ 
	var $log_warnings;
	
	/**
	 * Indica si se logean o no los E_ERROR y E_USER_ERROR
	 * @access public
	 * @var boolean
	 */ 
	var $log_errors;

	/**
	 * Setea como error handler al método handle_erro, además, si se especifica, setea
	 * el nombre del archivo al cual se van a logear los errores. Por defecto se logean los errores (E_ERROR)
	 * y los warnings (E_WARNING)
	 * 
	 * @access public
	 * @param $filename (string) nombre del archivo al cual se van a logear los errores
	 */ 
	function ErrorHandler($filename = '')
	{
		// Setear que errores se logean
		$this->log_notices = false;
		$this->log_errors = true;
		$this->log_warnings = true;

		if ($filename)
			$this->filename = $filename;
			
		set_error_handler(array(&$this, '_handleError'));
	}

	/**
	 * Formatea y guarda el mensaje en el archivo de log. Además se le agrega la fecha y hora 
	 * y un salto de linea
	 * 
	 * @access public
	 * @param $message (string) mensaje a logear
	 * @return void
	 */ 
	function log2file($message)
	{
		// Agregar la fecha y hora en la cual ocurrió en error
		$fecha = date('d/m/Y H:i:s');
		$message = $fecha . ';' . $message . "\r\n";
		error_log($message, 3, $this->filename);	
	}

	/**
	 * Restaura el handler de errores que estaba anteriormente a la creación de un objeto de esta clase
	 * 
	 * @access public
	 * @return void
	 */ 
	function restoreHandler() 
	{ 
		restore_error_handler(); 
	} 
	
	/**
	 * Este método es llamado cada vez que se produce un error, el cual llama al método 
	 * log2file para logear los errores a un archivo
	 * 
	 * @access private
	 * @param $errtype (int) codigo de error
	 * @param $errstr (string) mensaje de error
	 * @param $errfile (string) path del archivo donde ocurrió el error
	 * @param $errline (int) linea en la cual se produjo el error
	 * @return void
	 */ 
	function _handleError($errtype, $errstr, $errfile, $errline)
	{
		$log = 0;
	
		switch ($errtype)
		{
			case E_ERROR:
			case E_USER_ERROR:
				 $errtype = 'ERROR';
				 $log = $this->log_errors;
				 break; 
				 				 
			case E_WARNING:
			case E_USER_WARNING:
				 $errtype = 'WARNING';
				 $log = $this->log_warnings;
				 break;
			
			case E_NOTICE:
			case E_USER_NOTICE:
				 $errtype = 'NOTICE';
				 $log = $this->log_notices;
				 break;
		}
	
		if ($log)
		{
			// Logear errores
			$message = $errtype . ';Linea ' . $errline . ';' . $errstr . ';' . $errfile;	
			$this->log2file($message, $this->filename); 
		}
	}	
}
?>