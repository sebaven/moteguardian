<?php
include_once BASE_DIR ."clases/negocio/clase.ItemRonda.php";

class ItemRondaDAO extends AbstractDAO
{
	function getEntity()
	{
		return new ItemRonda();
	}
}

?>