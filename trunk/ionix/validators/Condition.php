<?
/**
 * Evalúa si se cumple o no una condición.
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
class Condition extends Validator
{
	/**
	 * Condición a evaluar
	 * @access private
	 * @var bool
	 */ 
	var $_condition;
	
	/**
	 * Evalúa si se cumple la condición especificada
	 * 
	 * @access public
	 * @param $condition (bool) condición a evaluar
	 * @param $key (sting) clave de internacionalización
	 */ 
	function Condition($condition, $key = '')
	{
		parent::Validator("", $key);
		$this->_condition = $condition;
	}

	/**
	 * @see Validator 
	 */ 
	function isValid()
	{	
		return $this->_condition;
	}	
}
?>