<?
/// Validador para campos de longitud maxima
/// @date 01/08/2006
/// @version 1.0
/// @author jbarbosa
/// @author amolinari
class MaxLengthValidator extends Validator
{
	
	var $_maxLength;
	
	/// Define al campo a asignar una longitud maxima, el valor de dicha longitud,
	/// y da la posiblidad de asignar el mensaje que se va a mostrar
	/// @access public
	/// @param $field (string) campo a validar
	/// @param $maxLength (int) longitud maxima del campo
	/// @param $key (sting) clave de internacionalización
	function MaxLengthValidator($field, $maxLength, $key = '')
	{
		$this->_maxLength = $maxLength;
		parent::Validator($field, $key);
	}

	/// Indica si el campo es válido
	/// @access public
	/// @return (bool) TRUE si cumple con la long. maxima, FALSE si no.
	function isValid()
	{	
		return  (strlen($_REQUEST[$this->_field]) < $this->_maxLength);
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
				
		return PropertiesHelper::GetKey('Campo demasiado largo: ') . $this->_field;				
	}	
}
?>