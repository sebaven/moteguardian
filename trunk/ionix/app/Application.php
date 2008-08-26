<?
/**
 * Clase que provee m�todos est�ticos para transferir el control a otros acciones y redireccionar a otras URLs.
 * 
 * ----------------------------------------
 *      iOnix - PHP Framework - 2006
 * ----------------------------------------
 * Jorge Barbosa      <jmbarbosa@gmail.com>
 * Alejandro Molinari <amolinari@gmail.com>
 * ----------------------------------------
 * 
 * @version 1.2
 * @package app
 * @author jbarbosa
 * @author amolinari
 */ 
class Application 
{
	/**
	 * Transfiere el control de la aplicaci�n a la acci�n indicada, pas�ndole el mensaje y los par�metros 
	 * especificados.
	 * 
	 * @access public
	 * @param $action (string) nombre de la acci�n
	 * @param $message (string) clave de internacionalizaci�n del mensaje
	 * @param $params (array) contiene cada uno de las variables con su valor
	 * @return void
	 */ 
	function Go($action, $message = '', $params = '')
	{
		$configuration = ActionConfiguration::getInstance();
		$acciones = $configuration->getAccionesByName();
		$defaults = $configuration->getDefaults();
		// Armar la lista de parametros para el link
		$list_params = '';
		if ($params)
		{
			foreach ($params as $param => $value)
			{
				$list_params .= '&' . $param . '=' . $value;
			}			
		}
		
		if ($acciones[$action])
		{
			if ($message)
				$m = '&m=' . $message;

			Application::Redirect($defaults['front'] . '?accion=' . $acciones[$action]['link'] . $m  . $list_params);
		}
		else
		{
			// Accion por default			
			Application::Redirect($defaults['front'] . '?accion=' . $defaults['nombre'] . $list_params);
		}
	}
	
	/**
	 * Devuelve un link a la accion dada. Si la acci�n no existe devuelve el link a la acci�n por
	 * defecto.
	 * Ej. index.php?accion=accion_ejemplo
	 * 
	 * @access public
	 * @param $action_name (string) Nombre de la acci�n
	 * @return (string) link
	 */
    function getLinkByActionName($action_name)
    {
        $configuration = new ActionConfiguration();
        $acciones = $configuration->getAccionesByName();
        $defaults = $configuration->getDefaults();
        
        if ($acciones[$action_name])
        {
            return $defaults['front'] . '?accion=' . $acciones[$action_name]['link'];
        }

        else
        {
            // Accion por default            
            return $defaults['front'] . '?accion=' . $defaults['nombre'];
        }            
    }
	
	/**
	 * Redirecciona la aplicaci�n a la URL especificada
	 * 
	 * @access public
	 * @param $url (string) url a la cual redireccionar
	 * @return void
	 */ 
	function Redirect($url)
	{
		// Cambiar el header
		header('location: ' . $url);
	
		// Terminar de ejecutar el script
		exit;
	}
}
?>