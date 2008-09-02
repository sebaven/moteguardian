<?php
include_once BASE_DIR ."clases/negocio/clase.LogRfid.php";

class LogRfidDAO extends AbstractDAO
{
	function getEntity()
	{
		return new LogRfid();
	}
}

?>