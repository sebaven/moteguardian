<?php
include_once BASE_DIR ."clases/negocio/clase.Guardia.php";

class GuardiaDAO extends AbstractDAO
{
	function getEntity()
	{
		return new Guardia();
	}
}

?>