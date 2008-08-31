<?
/**
 * Contiene la información de destino que se utiliza para pasar de una acción a la siguiente. Los destinos
 * pueden ser otra acción o una URL.
 * 
 * ----------------------------------------
 *      iOnix - PHP Framework - 2006
 * ----------------------------------------
 * Jorge Barbosa      <jmbarbosa@gmail.com>
 * Alejandro Molinari <amolinari@gmail.com>
 * ----------------------------------------
 *
 * @version 1.0
 * @package actions
 * @author jbarbosa
 * @author amolinari
 */
class ActionConnector
{
	/**
	 * Nombre de la acción 
	 *
	 * @var string
	 */
	var $_action;
	
	/**
	 * Mensaje que se le va a transferir a la próxima acción
	 *
	 * @var string
	 */
	var $_message;
	
	/**
	 * Parámetros que se le van a transferir a la próxima acción
	 *
	 * @var array
	 */
	var $_params;
	
	/**
	 * URL a la cual se va a transferir
	 *
	 * @var string
	 */
	var $_url;
	
	/**
	 * Constructor de la clase
	 *
	 * @access public
	 * @return void
	 */
	function ActionConnector()
	{		
	}
	
	/**
	 * Guarda los datos de la próxima acción a la cual se va a transferir al ejecutar el método execute()
	 * 
	 * @access public
	 * @param unknown_type $action
	 * @param unknown_type $message
	 * @param unknown_type $params
	 * @return void
	 */
	function setNextAction($action, $message = '', $params = '')
	{
		$this->_action = $action;
		$this->_message = $message;
		$this->_params = $params;
	}
	
	/**
	 * Guarda el valor de la URL a la cual se va a transferir cuando se llame al método execute()
	 *
	 * @access public
	 * @param unknown_type $url
	 * @return void
	 */
	function setRedirect($url)
	{
		$this->_url = $url;		
	}
	
	/**
	 * Transfiere el control a la siguiente acción o a la URL especificada
	 * 
	 * @access public
	 * @return void
	 */
	function execute()
	{
		// Se ejecuta la URL o la acción
		if ($this->_url)
		{
			Application::Redirect($this->_url);
		}
		else if ($this->_action)
		{
			Application::Go($this->_action, $this->_message, $this->_params);
		}
	}
}
?>