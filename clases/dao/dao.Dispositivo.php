<?php
include_once BASE_DIR ."clases/negocio/clase.Dispositivo.php";

class DispositivoDAO extends AbstractDAO
{
	function getEntity()
	{
		return new Dispositivo();
	}
}

?>