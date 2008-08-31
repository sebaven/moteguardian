<?php
include_once BASE_DIR ."clases/negocio/clase.Usuario.php";

class UsuarioDAO extends AbstractDAO
{
	function getEntity()
	{
		return new Usuario();
	}

	/**
	* Devuelve una sentencia SQL para realizar la busqueda de usuarios de 
	* acuerdo a los filtros especificados
	* 
	* @param $values (array asociativo) contiene como clave los campos a filtrar con los respectivos valores
	* @return (string) sentencia SQL
	*/
	
	function getSql($values = '')
	{
		$w = array();
		
		if ($values['usuario'])
			$w[] = "usuario LIKE '%" . addslashes($values['usuario']) . "%'";
			
		if ($values['nombre'])
			$w[] = "nombre = '" . addslashes($values['nombre']) . "'";
			
		if ($values['apellido'])
			$w[] = "apellido = '" . addslashes($values['apellido']) . "'";
			
		if ($values['email'])
			$w[] = "email = '" . addslashes($values['email']) . "'";
			
		if ($values['otros_mails'])
			$w[] = "otros_mails = '" . addslashes($values['otros_mails']) . "'";
			
		if ($values['telefono1'])
			$w[] = "telefono1 = '" . addslashes($values['telefono1']) . "'";
		
		if ($values['telefono2'])
			$w[] = "telefono2 = '" . addslashes($values['telefono2']) . "'";
			
		if ($values['id_rol'])
			$w[] = "id_rol = '" . addslashes($values['id_rol']) . "'";
					
			$w[] = "u.baja_logica = '".FALSE_."'";
			
		$sql  = "SELECT u.id, ";
		$sql .=	"		u.usuario, ";
		$sql .= "		u.nombre,";
		$sql .= "		u.apellido,";
		$sql .= "		u.email,";
		$sql .= "		u.otros_mails,";
		$sql .= "		u.telefono1,";
		$sql .= "		u.telefono2,";
		$sql .= "		rol.descripcion as rol ";
		$sql .=	"FROM usuario u ";
		$sql .= "INNER JOIN rol ON rol.id = u.id_rol ";		

		if ($w)
		{
			$sql .= "WHERE " . implode(' AND ', $w) . " ";
		}

		return $sql;
	}
}
?>