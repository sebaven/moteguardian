<?php
include_once BASE_DIR ."clases/negocio/clase.LogMota.php";

class LogMotaDAO extends AbstractDAO
{
	function getEntity()
	{
		return new LogMota();
	}
}

?>