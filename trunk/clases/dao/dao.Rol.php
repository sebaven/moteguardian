<?php
include_once BASE_DIR ."clases/negocio/clase.Rol.php";

class RolDAO extends AbstractDAO
{
	function getEntity()
	{
		return new Rol();
	}
}

?>