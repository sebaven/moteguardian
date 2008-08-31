<?
/**
 * Validador para campos numéricos.
 * 
 * ----------------------------------------
 *      iOnix - PHP Framework - 2006
 * ----------------------------------------
 * Jorge Barbosa      <jmbarbosa@gmail.com>
 * Alejandro Molinari <amolinari@gmail.com>
 * ----------------------------------------
 * 
 * @version 1.0
 * @package validators
 * @author jbarbosa
 * @author amolinari
 */ 
class Numeric extends Validator
{
	/**
	 * Constructor de la clase
	 * @access public
	 * @param $field (string) campo a validar
	 * @param $key (sting) clave de internacionalización
	 */ 
	function Numeric($field, $key = '')
	{
		parent::Validator($field, $key);
	}

	/**
	 * Indica si el campo es válido
	 * @access public
	 * @return (bool) TRUE si el campo es válido, FALSE si no lo es
	 */ 
	function isValid()
	{	
		if (trim(!empty($_REQUEST[$this->_field])))
		{
			if (!is_numeric($_REQUEST[$this->_field]))
			{
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * Retorna el mensaje de error cuando el campo no es válido
	 * @access public
	 * @return (string) mensaje de error
	 */ 
	function getError()
	{
		if ($this->_key)
		{
			return PropertiesHelper::GetKey($this->_key);
		}
				
		return 'El campo ' . $this->_field . ' debe ser un valor numérico';
	}	
}
?>