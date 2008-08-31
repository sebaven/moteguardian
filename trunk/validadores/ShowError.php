<?
/**
 * Validador para mostrar errores.
 *
 * @version 1.0
 * @author pfagalde
 */ 
class ShowError extends Validator
{
	/**
	 * Muestra el error con la clave especificada
	 * @access public
	 * @param $key (sting) clave de internacionalización
	 */ 
	function ShowError($key = '')
	{
		$this->_key = $key;
	}

	/**
	 * Siempre devuelve false dado que se fuerza el error
	 * @access public
	 * @return (bool) FALSE
	 */ 
	function isValid()
	{		
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