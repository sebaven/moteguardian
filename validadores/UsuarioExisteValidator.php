<?
include_once "clases/negocio/clase.Usuario.php";

/**
 * Validador de Usuarios
 * 
 * @version 1.0
 * @author pfagalde
 */ 
class UsuarioExisteValidator extends Validator
{
	var $username;
	
	/**
	 * Constructor de la clase
	 * 
	 * @access public
	 * @param $username (string) nombre de usuario
	 * @param $password (string) contraseña
	 * @param $key (sting) clave de internacionalizacion
	 */ 
	function UsuarioExisteValidator($username, $key = '')
	{
		$this->_key = $key;
		$this->username = $username;
	}
	
	/**
	 * @see Validator
	 */
	function isValid()
	{
		if ($this->username)
		{
			$usuario = new Usuario();
			$datos = $usuario->getByNombreUsuario($this->username);
			if (isset($datos['usuario'][0])) return false;
		}
		return true;
	}
}



?>