<?php
include_once BASE_DIR ."clases/negocio/clase.Sala.php";

class SalaDAO extends AbstractDAO
{
	function getEntity()
	{
		return new Sala();
	}
}

?>