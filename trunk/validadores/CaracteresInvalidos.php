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
class CaracteresInvalidos extends Validator
{
	/**
	 * Define si un usuario se puede crear con determinados caracteres (a-z, A-Z, 0-9)
	 * @access public
	 * @param $field (string) campo a validar
	 * @param $key (sting) clave de internacionalizaci�n
	 */ 
	function CaracteresInvalidos($field, $key = '')
	{
		parent::Validator($field, $key);
	}

	/**
	 * Indica si el campo es valido
	 * @access public
	 * @author dabiricha
	 * @return (bool) TRUE si el campo tiene caracteres validos, FALSE en caso contrario
	 */ 
	function isValid()
	{
		$longitud = strlen($_POST['usuario']);
		
		$i = 0;
		
		// Se valida que el campo tenga caracteres de a-z, A-Z y 0-9
		while ($i < $longitud){
			
			if ((($_POST['usuario'][$i] >= 'a') && ($_POST['usuario'][$i] <= 'z')) ||
				(($_POST['usuario'][$i] >= 'A') && ($_POST['usuario'][$i] <= 'Z')) ||
				(($_POST['usuario'][$i] >= '0') && ($_POST['usuario'][$i] <= '9'))){
					
					$i++;
				}
				else{
					
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
				
		return PropertiesHelper::GetKey('Especificar el campo ') . $this->_field;				
	}
}
?>