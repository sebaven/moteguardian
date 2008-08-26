<?
include_once "clases/service/clase.UsuarioService.php";
include_once "clases/negocio/clase.Usuario.php";

/**
 * Validador de Usuarios
 * 
 * @version 1.0
 * @author jbarbosa
 */ 
class UsuarioValidator extends Validator
{
	var $username;
	var $password;
	
	/**
	 * Constructor de la clase
	 * 
	 * @access public
	 * @param $username (string) nombre de usuario
	 * @param $password (string) contrase�a
	 * @param $key (sting) clave de internacionalizaci�n
	 */ 
	function UsuarioValidator($username, $password, $key = '')
	{
		$this->key = $key;
		$this->username = $username;
		$this->password = $password;
	}
	
	/**
	 * @see Validator
	 */
	function isValid()
	{
		// Verificar el tipo de autenticaci�n que se es� utilizando
		return UsuarioService::validateUser($this->username, $this->password);
	}
	
	/**
	 * @see Validator
	 */
	function getError()
	{
		if ($this->_key)
		{
			return PropertiesHelper::GetKey($this->_key);
		}
				
		return "Usuario o clave incorrectos";
	}	
}
?>