<?
/**
 * Singleton para el manejo de la configuraci�n de acciones por medio de archivos. Se puede cargar tanto en archivos 
 * planos como en XML. Por defecto trabaja con la configuraci�n por XML.
 * 
 * ----------------------------------------
 *      iOnix - PHP Framework - 2006
 * ----------------------------------------
 * Jorge Barbosa      <jmbarbosa@gmail.com>
 * Alejandro Molinari <amolinari@gmail.com>
 * ----------------------------------------
 * 
 * @version 1.3
 * @package app
 * @author jbarbosa
 * @author amolinari
 */ 
class ActionConfiguration
{
	/**
	 * Path al archivo de configuraci�n
	 * @access private
	 * @var string
	 */ 
	var $_config_file = ACTIONS_FILE;

	/**	
	 * Definicion de las acciones con el archivo respectivo donde se encuentra la clase (indexado por link) <br/>
	 * $_action['link_accion'] = array con los par�metros de la accion
	 * @access private
	 * @var array asociativo
	 */
	var $_actions_link;

	/**
	 * Definicion de las acciones con el archivo respectivo donde se encuentra la clase (indexado por nombre) <br/>
	 * $_action['nombre_accion'] = array con los par�metros de la accion
	 * @access private
	 * @var array asociativo
	 */ 
	var $_actions_name;

	/**
	 * Contiene la acci�n y el nombre del Front Controller de la aplicacion (Ejemplo: index.php)
	 * $_action['key'] = parametro
	 * @access private
	 * @var array asociativo
	 */ 
	var $_defaults;

	/**	
	 * Constructor de la clase. Carga la configuraci�n del archivo
	 * 
	 * @access private
	 * @return void
	 */ 
	function ActionConfiguration()
	{
		$this->loadConfiguration();
	}

	
	/**
	 * Devuelve la �nica instancia de esta clase
	 *
	 * @access public
	 * @return AppConfiguration
	 */
	function getInstance()
	{
		static $instance;
		
		if (!$instance)
			$instance = new ActionConfiguration();
			
		return $instance;
	}
	
	/**
	 * Devuelve un array que contiene para cada link de la accion los atributos correspondientes, de acuerdo al 
	 * archivo de configuraci�n
	 * 
	 * @access public
	 * @return (array) contiene las acciones con los atributos correspondientes
	 */ 
	function getAccionesByLink()
	{
		return $this->_actions_link;
	}
	
	/**
	 * Devuelve un array que contiene para cada nombre de accion los atributos correspondientes, de acuerdo al 
	 * archivo de configuraci�n.
	 * 
	 * @access public
	 * @return (array) contiene las acciones con los atributos correspondientes
	 */ 
	function getAccionesByName()
	{
		return $this->_actions_name;
	}

	/**
	 * Devuelve un array con los par�metros de la acci�n indicada
	 * 
	 * @access public
	 * @param (string) nombre de la acci�n
	 * @return (array) contiene los par�metros de la acci�n
	 */
	function getAccionByName($actionName)
	{
		return $this->_actions_name[$actionName];
	}
	
	/**	
	 * Devuelve un array que contiene para cada clave el valor de los defaults (Claves: nombre, front)
	 * 
	 * @access public
	 * @return (array) contiene las defaults con los atributos correspondientes
	 */ 
	function getDefaults()
	{
		return $this->_defaults;
	}

	/**
	 * Carga la configuraci�n desde el registro, si ya est� cargada, o del archivo de configuraci�n
	 * 
	 * @access private
	 * @return void
	 */ 
	function loadConfiguration()
	{
		if (! RegistryHelper::isConfiguration())
		{
			// Cargar el archivo de configuraci�n
			$this->loadConfigurationFromXml();

			// Grabar en el registro
			RegistryHelper::registerConfiguration($this->_actions);
		}
		else
		{
			// Cargar la configuraci�n del registro
			$this->_actions = RegistryHelper::getConfiguration();
		}
	}

	/**
	 * Carga en $this->_actions el archivo de configuraci�n. La configuraci�n se obtiene del archivo indicado en _config_file
	 * 
	 * @access private
	 * @return void
	 */ 
	function loadConfigurationFromXml()
	{
		// Abrir el archivo XML y obtener el root
		$dom = new DomDocument();
		
		$dom->load(realpath(".") . '/' . $this->_config_file);

		
		// Procesar el default (es un solo elemento)
		$defaults = $dom->getElementsByTagname("default");
		foreach ($defaults as $default)
		{
			// Nombre de la acci�n
			$nombre = $default->getElementsByTagname('nombre')->item(0)->firstChild->data;
	
			// Front de la aplicacion
			$front = $default->getElementsByTagname('front')->item(0)->firstChild->data;
		}

		// Guardar los defaults
		$this->_defaults['nombre'] = $nombre;
		$this->_defaults['front'] = $front;
				
		// Procesar las acciones 
		$acciones = $dom->getElementsByTagname("accion");
		foreach ($acciones as $accion)
		{
			// Nombre de la acci�n
			$nombre = $accion->getElementsByTagname('nombre')->item(0)->firstChild->data;

			// Link de la acci�n
			$link = $accion->getElementsByTagname('link')->item(0)->firstChild->data;

			// Nombre del m�dulo
			$modulo = $accion->getElementsByTagname('modulo')->item(0)->firstChild->data;

			// Nombre de la clase
			$clase = $accion->getElementsByTagname('clase')->item(0)->firstChild->data;

			// Indexar las acciones por el link
			$this->_actions_link[$link] = array('nombre' => $nombre, 'link' => $link, 'modulo' => $modulo, 'clase' => $clase);

			// Indexar las acciones por el nombre			
			$this->_actions_name[$nombre] = array('nombre' => $nombre, 'link' => $link, 'modulo' => $modulo, 'clase' => $clase);
		}
	}
}
?>