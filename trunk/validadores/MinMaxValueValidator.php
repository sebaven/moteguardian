<?
/// Validador para minimos / maximos numericos
/// @date 01/08/2006
/// @version 1.0
/// @author jbarbosa
/// @author amolinari
class MinMaxValueValidator extends Validator
{
	
	var $_minValue;
	var $_maxValue;
	
	/// Define al campo a asignar limites minimos y maximos, 
	/// y dichos valores.
	/// @access public
	/// @param $field (string) campo a validar
	/// @param $minValue (int) valor minimo
	/// @param $maxValue (int) valor maximo
	/// @param $key (sting) clave de internacionalización
	function MinMaxValueValidator($field, $minValue, $maxValue, $key = '')
	{
		$this->_minValue = $minValue;
		$this->_maxValue = $maxValue;
		
		parent::Validator($field, $key);
	}

	/// Indica si el campo es válido
	/// @access public
	/// @return (bool) TRUE si cumple con los limites maximos y minimos, FALSE si no.
	function isValid()
	{	
		return (($_REQUEST[$this->_field] >= $this->_minValue) && ($_REQUEST[$this->_field] <= $this->_maxValue));
	}
	
	/// Retorna el mensaje de error cuando el campo no es válido
	/// @access public
	/// @return (string) mensaje de error
	function getError()
	{
		if ($this->_key)
		{
			return PropertiesHelper::GetKey($this->_key);
		}
				
		return PropertiesHelper::GetKey('Campo fuera de limite: ') . $this->_field;				
	}	
}
?>