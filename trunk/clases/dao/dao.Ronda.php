<?php
include_once BASE_DIR ."clases/negocio/clase.Ronda.php";

class RondaDAO extends AbstractDAO
{
	function getEntity()
	{
		return new Ronda();
	}
}

?>