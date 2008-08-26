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
 * @author dabiricha
 */ 
class RequiredNumericZero extends Validator
{
	/**
	 * Define al campo especificado como obligatorio, con la posiblidad de asignar el mensaje que se va a mostrar
	 * @access public
	 * @param $field (string) campo a validar
	 * @param $key (sting) clave de internacionalizaci�n
	 */ 
	function RequiredNumericZero($field, $key = '')
	{
		parent::Validator($field, $key);
	}

	/**
	 * Indica si el campo es v�lido
	 * @access public
	 * @return (bool) TRUE si el est� completo, FALSE si no lo est�
	 */ 
	function isValid()
	{
		$data = trim($_REQUEST[$this->_field]);
		// Se valida que sea un dato numerico
		if (positive_number($data))
		{
			// Si es numerico se convierte a entero
			$data = intval($data);
			if ($data >= 0){
				return true;
			}
		}

		return false;
	}
	
	/**
	 * Retorna el mensaje de error cuando el campo no es v�lido
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