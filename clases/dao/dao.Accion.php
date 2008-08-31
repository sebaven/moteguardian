<?php
include_once BASE_DIR."clases/negocio/clase.Accion.php";

class AccionDAO extends AbstractDAO
{
	function getEntity()
	{
		return new Accion();
	}
}
?>