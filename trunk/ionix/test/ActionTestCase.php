<?
/**
 * Clase Base de testeo de acciones
 * 
 * ----------------------------------------
 *      iOnix - PHP Framework - 2006
 * ----------------------------------------
 * Jorge Barbosa      <jmbarbosa@gmail.com>
 * Alejandro Molinari <amolinari@gmail.com>
 * ----------------------------------------
 * 
 * @package test
 * @author jbarbosa
 * @author amolinari
 * @version 1.0
 */
class ActionTestCase extends UnitTestCase
{		
	/**
	 * Inicializa una transacción
	 * 
	 * @access protected
	 * @return voi
	 */
	function setUp() {
		$db = ConnectionManager::getConnection();    
		$db->begin();
    }
    
	/**
	 * Genera el rollback de la transacción actual
	 * 
	 * @access protected
	 * @return void
	 */
    function tearDown() {
		$db = ConnectionManager::getConnection();    
		$db->rollback();    
    }
}
?>