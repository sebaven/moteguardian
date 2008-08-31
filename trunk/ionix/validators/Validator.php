<?
/**
 * Clase Base para los validadores. Se indica un campo y la clave de internacionalizac�n si la validaci�n 
 * es incorrecta
 * 
 * ----------------------------------------
 *      iOnix - PHP Framework - 2006
 * ----------------------------------------
 * Jorge Barbosa      <jmbarbosa@gmail.com>
 * Alejandro Molinari <amolinari@gmail.com>
 * ----------------------------------------
 * 
 * @version 1.1
 * @package validators
 * @author jbarbosa
 * @author amolinari
 */
class Validator
{
	/**
	 * Clave de internacionalizaci�n
	 * @access private
	 * @var string
	 */ 
	var $_key;
	
	/**
	 * Campo a validar
	 * @access private
	 * @var string
	 */ 
	var $_field;

	/**
	 * Constructor de la clase
	 * @access public
	 * @param $field (string) campo a validar
	 * @param $key (sting) clave de internacionalizaci�n
	 */ 
	function Validator($field, $key = '')
	{
		$this->_field = $field;
		$this->_key = $key;
	}
	
	/**
	 * Indica si el campo es v�lido
	 * @access public
	 * @return (bool) TRUE si el campo es v�lido, FALSE si no lo es
	 */ 
	function isValid()
	{
		return true;
	}
	
	/**
	 * Retorna el mensaje de error cuando el campo no es v�lido
	 * @access public
	 * @return (string) mensaje de error
	 */ 
	function getError()
	{
		return PropertiesHelper::GetKey($this->_key);
	}
}
?>