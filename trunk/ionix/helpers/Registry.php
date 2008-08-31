<?
/**
 * Clase en la cual se pueden registrar claves con valores.
 * 
 * ----------------------------------------
 *      iOnix - PHP Framework - 2006
 * ----------------------------------------
 * Jorge Barbosa      <jmbarbosa@gmail.com>
 * Alejandro Molinari <amolinari@gmail.com>
 * ----------------------------------------
 * 
 * @version 1.0
 * @package helpers
 * @author jbarbosa
 * @author amolinari
 */ 
class Registry
{
	/**
	 * Almacena todas las entradas con los datos
	 * 
	 * @access private
	 * @var array asociativo
	 */ 
	var $_registry;

	/**
	 * Constructor de la clase. 
	 * @access public
	 */
	function Registry()
	{
		$this->_registry = array();
	}
	
	/**
	 * Registra una clave con su valor correspondiente
	 * 
	 * @access public
	 * @param $key (string) nombre de la clave a registrar
	 * @param $item (object) valor de la clave a registrar.
	 */ 
	function setEntry($key, &$item)
	{
		$this->_registry[$key] = $item;
	}
	
	/**
	 * Devuelve una referencia al valor de la clave especificada
	 * 
	 * @access public
	 * @param $key (string) nombre de la clave de la cual se quiere obtener el valor
	 * @return (object) referencia al valor de la clave especificada.
	 */ 
	function &getEntry($key)
	{
		return $this->_registry[$key];
	}	
}
?>