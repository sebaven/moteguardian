<?php
include_once BASE_DIR ."clases/negocio/clase.LogAlarma.php";

class LogAlarmaDAO extends AbstractDAO
{
	function getEntity()
	{
		return new LogAlarma();
	}
}

?>