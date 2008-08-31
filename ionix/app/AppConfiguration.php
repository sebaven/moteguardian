<?
/**
 * Singleton para cargar y administrar la configuración de la aplicación por medio de un archivo XML. El archivo 
 * de configuración está definido en APP_CONFIG_FILE.
 * 
 * ----------------------------------------
 *      iOnix - PHP Framework - 2006
 * ----------------------------------------
 * Jorge Barbosa      <jmbarbosa@gmail.com>
 * Alejandro Molinari <amolinari@gmail.com>
 * ----------------------------------------
 * 
 * @version 1.0
 * @package app
 * @author jbarbosa
 * @author amolinari
 */ 
class AppConfiguration
{
	/**
	 * Path al archivo de configuración
	 * @access private
	 * @var string
	 */ 
	var $_config_file = APP_CONFIG_FILE;
	
	/**
	 * Array donde se almacena la configuración de la aplicación
	 * @access private
	 * @var array
	 */
	var $_settings;
	
	/**
	 * Constructor de la clase
	 * 
	 * @access private
	 */
	function AppConfiguration()
	{
		$this->loadConfigurationFromXml();
	}

	/**
	 * Devuelve la única instancia de esta clase
	 *
	 * @access public
	 * @return AppConfiguration
	 */
	function getInstance()
	{
		static $instance;
		
		if (!$instance)
			$instance = new AppConfiguration();
			
		return $instance;
	}
	
	/**
	 * Procesa y carga en variables de instancia la configuración de la aplicación que se encuentra en el 
	 * archivo XML APP_CONFIG_FILE. Si el archivo no se encuentra no devuelve ningún error.
	 * 
	 * @access private
	 * @return void
	 */ 
	function loadConfigurationFromXml()
	{				
		// Abrir el archivo XML y obtener el root
		$dom = new DomDocument();
		@$dom->load(realpath(".") . '/' . $this->_config_file);
		
		if (!$dom)
			return ;

		$this->_processI18N($dom);
		$this->_processAuthenticationMethod($dom);
		$this->_processSettings($dom);
	}
	

	/**
	 * Devuelve el nombre de archivo para el idioma indicado, de acuerdo a la configuracion en el XML correspondiente
	 * 
	 * @access public
	 * @param (string) $language idioma del cual se desea obtener el nombre de archivo asociado
	 * @return (string) nombre de archivo de internacionalización. Si no existe devuelve vacío.
	 */
	function getLanguageFileName($language)
	{
		$languages = $this->_settings["i18n"];
		
		foreach ($languages as $l)
		{
			if ($l[0] == $language)
			{
				return $l[1];
			}
		}
		
		return "";
	}
	
	function getAuthenticationMethod(){
		return $this->_settings["authentication"];
	}
	
	/**
	 * Devuelve la configuración del manejo de errores definido en el archivo de configuración
	 *
	 * @access public
	 * @return bool true si está seteado, false en caso contrario
	 */
	function getErrorHandlerSetting()
	{
		if ($this->_settings["errorhandler"])
			return $this->_settings["errorhandler"];
			
		return false;
	}
	
	/**
	 * Procesa los tags correspondientes a internacionalización de la aplicación. Carga la configuración en
	 * variables de instancia de esta clase.
	 * 
	 * @access private
	 * @param object $dom referencia al objeto DOM del archivo de configuración de la aplicación
	 * @return void
	 */ 
	function _processI18N($dom)
	{
		// Un solo elemento <i18n>
		$i18n = $dom->getElementsByTagname('i18n')->item(0);

		// Archivos internacionalización
		$files = $i18n->getElementsByTagname('file');
		foreach ($files as $file)
		{
			// Nombre del archivo
			$filename = $file->firstChild->data;
			// El único atributo es language (Ejemplo: <file language="es">site_es.properties</file>)
			$value = $file->getAttributeNode("language")->value;
			// Grabar la configuración
			$this->_settings["i18n"][] = array($value, $filename);
		}		
	}
	
	/**
	 * Procesa los tags correspondientes a internacionalización de la aplicación. Carga la configuración en
	 * variables de instancia de esta clase.
	 * 
	 * @access private
	 * @param object $dom referencia al objeto DOM del archivo de configuración de la aplicación
	 * @return void
	 */ 
	function _processAuthenticationMethod($dom)
	{
		$authentication = $dom->getElementsByTagname("authentication")->item(0);

		$value = $authentication->attributes->item(0)->firstChild->data;
			
		// Grabar la configuración
		$this->_settings["authentication"] = $value;
	}
		
	/**
	 * Procesa los tags correspondientes a manejo de errores de la aplicación. Carga la configuración en
	 * variables de instancia de esta clase.
	 * 
	 * @access private
	 * @param object $dom referencia al objeto DOM del archivo de configuración de la aplicación
	 * @return void
	 */ 
	function _processSettings($dom)
	{
		// Procesar el elemento <errorhandler>
		$errorhandler = $dom->getElementsByTagname("errorhandler")->item(0)->firstChild->data;
		// Grabar la configuración
		if ($errorhandler != ""){
			$bool = ($errorhandler == "true") ? true : false;
			$this->_settings["errorhandler"] = $bool;
		}
	}
}
?>