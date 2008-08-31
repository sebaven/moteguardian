<?
/**
 * Validador para campos de tipo fecha.
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
class DateField extends Validator
{
	/**
	 * Indica si la fecha es obligatoria
	 * @access private
	 * @var bool
	 */ 
	var $_required;
	
	/**
	 * Constructor de la clase. Cada parte de la fecha se forma con el nombre del campo m�s el sufijo _anio, _mes o _dia.
	 * @access public
	 * @param $field (string) campo a validar
	 * @param $key (sting) clave de internacionalizaci�n
	 * @param $required (bool) indica si el campo es obligatorio
	 */ 
	function DateField($field, $key = '', $required = false)
	{
		parent::Validator($field, $key);
		$this->_required = $required;
	}

	/**
	 * Indica si el campo es v�lido
	 * @access public
	 * @return (bool) TRUE si el campo es v�lido, FALSE si no lo es
	 */ 
	function isValid()
	{
		$anio = $this->_field . '_anio';
		$mes  = $this->_field . '_mes';
		$dia  = $this->_field . '_dia';
		
		// Validar si la fecha es obligatoria
		if ($this->_required)
		{
			if (! ($_REQUEST[$anio] && $_REQUEST[$mes] && $_REQUEST[$dia]))
			{
				return false;
			}
		}
		
		// Validar si la fecha es v�lida
		if ($_REQUEST[$anio] || $_REQUEST[$mes] || $_REQUEST[$dia])
		{					
			if (!checkdate(intval($_REQUEST[$mes]), intval($_REQUEST[$dia]), intval($_REQUEST[$anio])) || intval($_REQUEST[$anio] < 1900))
			{
				return false;
			}
		}
	
		return true;
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
				
		return 'La Fecha de ' . $this->_field . ' no es v�lida';
	}	
}
?>