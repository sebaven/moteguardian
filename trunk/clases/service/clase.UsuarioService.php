<?
include_once BASE_DIR ."clases/negocio/clase.Usuario.php";
/**
 * Service para Usuarios
 * 
 * @author pfagalde
 */
class UsuarioService
{
	function validateUser($username, $password)
	{
		if ($username && $password)
		{
			$usuario = new Usuario();
			$datos = $usuario->getByNombreUsuario($username);

			// Si el usuario esta deshabilitado
			if ($datos['baja_logica'][0] == 1)
				return false;
			
			if($datos['clave'][0] != md5($password))
			{
				return false;
			}
			
			return true;
		}
	
		return false;
	}
}
?>