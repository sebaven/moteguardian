<?
/// @date 07/01/2008
/// @version 1.0
/// @author pfagalde
include_once BASE_DIR ."clases/negocio/clase.Rol.php";

class Usuario extends AbstractEntity
{
	/// Nombre de la tabla sobre a la cual accede la clase
	/// @protected
	/// @var string
	var $_tablename = 'usuario';
	
	/// Nombre de los campos, menos el campo id
	/// @protected
	/// @var array
	var $_fields = array
	(
		'usuario' 	 => 'varchar',
		'clave' => 'varchar',
		'nombre' => 'varchar',
		'apellido' => 'varchar',
		'email' => 'varchar',
		'otros_mails' => 'varchar',
		'telefono1' => 'varchar',
		'telefono2' => 'varchar',
		'id_rol' => 'int',
		'baja_logica' => 'int'
	);

	var $id;
	var $usuario;
	var $clave;
	var $nombre;
	var $apellido;
	var $email;
	var $otros_mails;
	var $telefono1;
	var $telefono2;
	var $id_rol;
	var $baja_logica;

	/// Devuelve a un usuario seg�n su username 
	/// @access public
	/// @return (usuario)
	function getByNombreUsuario($usuario) {
		$sql  = "SELECT * ";
		$sql .= "FROM $this->_tablename ";
		$sql .= "WHERE baja_logica = 0 ";
		$sql .= "AND usuario = '".addslashes($usuario)."'";
		$rs = $this->_db->leer($sql);
		
		return $this->_rs2array($rs);
	}
	
	/**
	 * Esta funcion valida que no se este poniendo la misma password que tenia
	 * anteriormente
	 * @access public
	 * @author dabiricha
	 * @param $password: nuevo password que se quiere comprobar que no sea igual al
	 * 			password anterior
	 * @return true en caso de que las claves sean diferentes, false en caso
	 * 			contrario
	 */
	function validarPasswordRepetido($password){
		
		return $this->clave != md5($password);
	}
	
	/// Devuelve el nombre del rol a partir del username
	/// @access public
	/// @param (string) $username 
	/// @return (Rol) El rol del usuario.
	function getRolByNombreUsuario($usuario)
	{
		$data = $this->getByNombreUsuario($usuario);
		$rol = new Rol($data[id_rol][0]);
		
		return $rol;
	}	
	
	/**
	 * Esta funcion elimina todos los usuarios que tienen el nombre de usuario $username
	 * @access public
	 * @author dabiricha
	 * @param $username (string): nombre de usuario que se quiere eliminar
	 */
	function deleteUsuario($username){

		$sql  = "DELETE FROM " . $this->_tablename . " WHERE usuario = '" . $username . "'";

		$rs = $this->_db->leer($sql);
	}
}

?>
