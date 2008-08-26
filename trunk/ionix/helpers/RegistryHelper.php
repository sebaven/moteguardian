<?
/// Clase helper de la clase Registry, a traves de m�todos ESTATICOS. 
/// @date 07/01/2006
/// @version 1.0
/// @author jbarbosa
/// @author amolinari
class RegistryHelper
{
	/// Inicializa el Registro
	/// @access public
	function init()
	{
		// Inicializar Registry
		$registry = new Registry();
		$_SESSION['registry'] = &$registry;
	}
	
	/// Destruye todas las variables seteadas en el Registro
	/// @access public
	function finish()
	{
		session_unset();
		session_destroy();
	}

	/// Determina si el Registro est� inicializado
	/// @access public
	/// @return (bool) true si el Registro est� inicializado, false si no lo est�
	function isInit()
	{
		return isset($_SESSION['registry']);	
	}

	function isConfiguration()
	{
		// Tomar la referencia al registro
		$registry = &$_SESSION['registry'];

		if ($registry->getEntry('CONFIGURATION'))
		{
			return true;
		}
		
		return false;
	}
	
	function registerConfiguration($configuration)
	{
		// Tomar la referencia al registro
		$registry = &$_SESSION['registry'];

		// Registrar los datos del usuario logeado
		$registry->setEntry('CONFIGURATION', $configuration);
	}
	
	function getConfiguration()
	{
		// Tomar la referencia al registro
		$registry = &$_SESSION['registry'];

		return $registry->getEntry('CONFIGURATION');
	}
	
	/// Registra los datos del usuario logeado.
	/// @access public
	/// @param $username  (string) nombre de usuario
	/// @param $id_usuario (int) id del usuario
	function registerUser($username, $id_usuario, $rol)
	{
		// Tomar la referencia al registro
		$registry = &$_SESSION['registry'];

		// Registrar los datos del usuario logeado
		$registry->setEntry('USERNAME', $username);
		$registry->setEntry('ID_USER', $id_usuario);
		$registry->setEntry('ROL',$rol);		
	}

	/// Devuelve el username del usuario logeado
	/// @access public
	/// @return (string) username del usuario
	function getUsername()
	{
		// Tomar la referencia al registro
		$registry = &$_SESSION['registry'];

		// Registrar los datos del usuario logeado		
		return $registry->getEntry('USERNAME');
		
	}	

	/// Devuelve el rol del usuario logeado
	/// @access public
	/// @return (string) rol
	function getRolUsuario()
	{
		// Tomar la referencia al registro
		$registry = &$_SESSION['registry'];

		// Registrar los datos del usuario logeado		
		return $registry->getEntry('ROL');
	}		
	
	/// Devuelve el id del usuario logeado
	/// @access public
	/// @return (int) id del usuario
	function getIdUsuario()
	{
		// Tomar la referencia al registro
		$registry = &$_SESSION['registry'];

		// Registrar los datos del usuario logeado		
		return $registry->getEntry('ID_USER');
	}

	/// Indica si hay un usuario logeado
	/// @access public
	/// @return (bool) true si hay un usuario logeado, false en caso contrario
	function isUserLogged()
	{
		// Tomar la referencia al registro
		if ($registry = &$_SESSION['registry'])
		{
			if ($registry->getEntry('USERNAME'))
			{
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Registra el idioma especificado
	 * 
	 * @access public
	 * @param (string) $language
	 * @return void
	 */
	function registerLanguage($language)
	{
		// Tomar la referencia al registro
		$registry = &$_SESSION['registry'];

		// Registrar los datos del usuario logeado
		$registry->setEntry('LANGUAGE', $language);
	}
	
	/**
	 * Indica si est� registrado alg�n idioma
	 * 
	 * @access public
	 * @return (bool) true si hay un idioma registrado, false en caso contrario
	 */
	function isLanguageRegistered()
	{
		return RegistryHelper::_isKey('LANGUAGE');
	}	
	
	/**
	 * Devuelve el idioma registrado
	 *
	 * @access public
	 * @return (string) idioma registrado
	 */
	function getLanguage()
	{
		// Tomar la referencia al registro
		$registry = &$_SESSION['registry'];

		return $registry->getEntry('LANGUAGE');		
	}
	
	/**
	 * Indica si la clave $key est� registrada
	 * 
	 * @access private
	 * @param (string) $key nombre de la clave
	 * @return (bool) true si la clave est� registrada, false en caso contrario
	 */
	function _isKey($key)
	{
		// Tomar la referencia al registro
		if ($registry = &$_SESSION['registry'])
		{
			if ($registry->getEntry($key))
			{
				return true;
			}
		}
		
		return false;
	}	
}
?>