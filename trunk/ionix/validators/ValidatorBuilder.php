<?
/**
 * Administra lista de validadores que luego se ejecutan en conjunto devolviendo una lista con los errores
 * encontrados.
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
class ValidatorBuilder
{
	/**
	 * Lista de validadores. $_validators[0]: validator, $_validators[1]: true/false (always validate)
	 * @access private
	 * @var array
	 */ 
	var $_validators;

	/**
	 * Constructor de la clase
	 * @access public
	 */ 
	function ValidatorBuilder()
	{
		 $this->_validators = array();
	}
	
	/**
	 * Agrega un validador (Validator) a la lista de validadores, con una condición que se tendrá
	 * en cuenta de acuerdo a que método se llame para ejecutar la lista de validadores.
	 * 
	 * @access public
	 * @param $validator (object) contiene la implementación de un validador
	 * @param $always_validate (bool) true si el validador se ejecuta siempre, false si no se ejecuta cuando
	 * se llame al método validateOnCondition
	 * @return void
	 */ 
	function add(&$validator, $always_validate = false)
	{
		$this->_validators[] = array(&$validator, $always_validate);
	}

	/**
	 * Ejecuta todos los validadores sin tener en cuenta la condición de validación
	 * 
	 * @access public
	 * @return (object) objeto ErrorCollection con la lista de mensajes de error (o lista vacía)
	 */
    function validateAll()
    {
        return $this->_validate(true);
    }
    
	
	/**
	 * Ejecuta los validadores que teniendo en cuenta si se valida siempre o no.
	 * 
	 * @access public
	 * @return (object) objeto ErrorCollection con la lista de mensajes de error (o lista vacía)
	 */
    function validateOnCondition()
    {
        return $this->_validate(false);
    }
    
	/**
	 * Ejecuta todas las validaciones
	 * 
	 * @access private
	 * @param $always (bool) true si se ejecutan todos los validarores sin considerar condiciones, false en 
	 * caso contrario
	 * @return (object) objeto ErrorCollection con la lista de mensajes de error (o lista vacía)
	 */ 
    function _validate($always)
    {
		$errors = new ErrorCollection();

        foreach ($this->_validators as $validator_data)
        {
            $validator = $validator_data[0];
            $always_validate = $validator_data[1];

            if ($always || $always_validate) {            
                if (! $validator->isValid())
                {
                    // Agregar el error de validación
                    $errors->add($validator->getError());
                }
            }
        }
        
        return $errors;        
    }	
}
?>