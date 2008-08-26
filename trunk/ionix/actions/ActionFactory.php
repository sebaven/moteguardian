<?
/**
 * Factory para la construcción de acciones. Devuelve el nombre de la clase donde que corresponde a la 
 * acción especificada, de manera de hacer un include de ese archivo solamente.
 * 
 * ----------------------------------------
 *      iOnix - PHP Framework - 2006
 * ----------------------------------------
 * Jorge Barbosa      <jmbarbosa@gmail.com>
 * Alejandro Molinari <amolinari@gmail.com>
 * ----------------------------------------
 *
 * @version 1.2
 * @package actions
 * @author jbarbosa
 * @author amolinari
 */
class ActionFactory
{
	/**
	 * Definición de las acciones con el archivo respectivo donde se encuentra la clase (accion => clase)
	 * 
	 * @access private
	 * @var arraty asociativo
	 */
	var $_actions;
	
	var $_action_default;
	
	/**
	 * Acción pasada en el constructor
	 * 
	 * @access private
	 * @var string
	 */
	var $_action;
	
	/**
	 * Constructor de la clase. Carga la configuración de las acciones y opcionalmente carga el nombre de la clase que se va a crear
	 * mediante el método create
	 * 
	 * @access public
	 * @param $action (string) nombre de la acción
	 * @return void
	 */
	function ActionFactory($action = '')
	{
		// Cargar la configuración de acciones con las clases correspondientes
		$config = ActionConfiguration::getInstance();
		$this->_actions = $config->getAccionesByLink();

		// Datos de la acción por defecto
		$defaults = $config->getDefaults();
		$actionDefault = $config->getAccionByName($defaults['nombre']);
		$this->_action_default = $actionDefault["link"];
		
		// Guardar la accion que viene por parametro
		if (!empty($action))
		{
			$this->_action = $action;
		}
	}

	/**
	 * Devuelve el nombre de la clase asociada con la acción especificada o el nombre de una clase por defecto si no hay asociación
	 * 
	 * @access public	
	 * @param $action (string) nombre de la acción
	 * @return (array) contiene como claves el nombre y el modulo de la clase
	 */ 
	function create($action = '')
	{
		// Guardar la accion que viene por parametro
		if (!empty($action))
		{
			$this->_action = $action;
		}

        // Definir el archivo de clase en base a la accion
        if (array_key_exists($this->_action, $this->_actions))
        {
            $clase['nombre'] = $this->_actions[$this->_action]['nombre'];
            $clase['clase'] = $this->_actions[$this->_action]['clase'];
            $clase['modulo'] = $this->_actions[$this->_action]['modulo'];
        }
        else
        {
        	// Clase por defecto
            $clase['nombre'] = $this->_actions[$this->_action_default]['nombre'];
            $clase['clase'] = $this->_actions[$this->_action_default]['clase'];
            $clase['modulo'] = $this->_actions[$this->_action_default]['modulo'];
        }
		
		// Devolver el array con los datos de la clase
		return $clase;
	}	
}	
?>