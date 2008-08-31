<?
/**
 * Test Case para la persistencia de los objetos que heredan de Base, por defecto se genera un test de persistencia con datos	
 * de prueba, el mismo debe ser sobreescrito para cargar datos válidos.
 * 
 * ----------------------------------------
 *      iOnix - PHP Framework - 2006
 * ----------------------------------------
 * Jorge Barbosa      <jmbarbosa@gmail.com>
 * Alejandro Molinari <amolinari@gmail.com>
 * ----------------------------------------
 * 
 * @version 1.1
 * @package test
 * @author jbarbosa
 * @author amolinari
 */ 
class BaseTestCase extends UnitTestCase
{		
	/**
	 * Inicializa una transacción
	 * @access protected
	 * @return void
	 */ 
	function setUp() {
		$db = ConnectionManager::getConnection();    
		$db->begin();
    }
    
	/**
     * Genera el rollback de la transacción actual
	 * @access protected
	 * @return void
	 */ 
    function tearDown() {
		$db = ConnectionManager::getConnection();    
		$db->rollback();    
    }
    
	/**
     * Testea que se grabe un objeto en la base de datos y luego se recupere correctamente
	 * @access protected
	 * @return void
	 */ 
	function testPersistencia()
	{
		// Insertar el objeto
		$object = $this->getObject();
		$this->insertObject($object);
		@$object->insert();
		$id = $object->id;

		// Cargar el objeto insertado
		if ($id)
		{
			$load_object = $this->getObject();
			$load_object->load($id);
		}
		else
		{
			$this->assertTrue(false, "No se pudo grabar el objeto");
		}

		if ($id)
		{
			// Comparar si son iguales
			$this->assertTrue($this->_equalObjects($object, $load_object), "El objeto no se grabó correctamente");
		}
	}

	/**
	 * Este método puede ser sobreescrito para setear los atributos del objeto antes de la inserción
	 * @access protected
	 * @param $object (object) instancia del objeto que se va a testar
	 * @return void
	 */ 
	function insertObject(&$object)
	{			
		// Setear los valores de los atributos con datos por defecto
		foreach ($object->_fields as $field => $type)
		{		
			$object->$field = $this->_getDefaultData($type);
		}
	}

	/**
	 * Este método debe ser sobreescrito para que devuelva una instancia del objeto sobre el cual se está haciendo el test
	 * @access protected
	 * @return (object) instancia del objeto sobre el cual se va a hacer el test
	 */ 
	function getObject()
	{
		// Sobreescribir
	}
		
	/**
	 * Compara si los atributos de los objetos especificados son iguales
	 * @access private
	 * @param $object (object) objeto a comparar
	 * @param $load_object (object) objeto con el que se va a comparar
	 * @return (bool) true si son iguales, false si no lo son
	 */ 
	function _equalObjects($object, $load_object)
	{
		foreach ($object->_fields as $field => $type)
		{
			if ($object->$field != '')
			{
				if ($type == TYPE_DATE)
				{
					// Para las fechas hay que convertir los valores ya que al levantarlo de 
					// la base puede quedar en formato diferente
					$object->$field = strtotime($object->$field);
					$load_object->$field = strtotime($load_object->$field);
				}
				
				if ($object->$field != $load_object->$field)
				{					
					// Los objetos son diferentes
					return false;
				}
			}				
		}

		return true;
	}
	
	/**
	 * Devuelve un dato de ejemplo para el tipo especficado
	 * @access private
	 * @param $type (string) tipo de dato
	 * @return (string/int) dato de ejemplo para el tipo especificado
	 */ 
	function _getDefaultData($type)
	{
		switch ($type)
		{
			case TYPE_INT: return 1;
			case TYPE_VARCHAR: return 'V';	
			case TYPE_TEXT: return 'text';
			case TYPE_BOOLEAN: return 't';
			case TYPE_DATE: return '2005/01/31';
		}
	}
}
?>