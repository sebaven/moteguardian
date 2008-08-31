<?php
include_once BASE_DIR ."clases/negocio/clase.ValidadorDuplicados.php";

class ValidadorDuplicadosDAO extends AbstractDAO
{
	function getEntity() 
	{
		return new ValidadorDuplicados();
	}	
}
?>