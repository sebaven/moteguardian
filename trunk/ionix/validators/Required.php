<?
/**
 * Validador para campos obligatorios.
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
class Required extends Validator
{
	/**
	 * Define al campo especificado como obligatorio, con la posiblidad de asignar el mensaje que se va a mostrar
	 * @access public
	 * @param $field (string) campo a validar
	 * @param $key (sting) clave de internacionalización
	 */ 
	function Required($field, $key = '')
	{
		parent::Validator($field, $key);
	}

	/**
	 * Indica si el campo es válido
	 * @access public
	 * @return (bool) TRUE si el está completo, FALSE si no lo está
	 */ 
	function isValid()
	{		
		$data = trim($_REQUEST[$this->_field]);
		return !empty($data) && !($data === "0") && !($data === 0) && (trim($data) != "");
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
				
		return PropertiesHelper::GetKey('Especificar el campo ') . $this->_field;				
	}	
}
?>