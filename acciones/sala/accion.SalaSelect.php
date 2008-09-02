<?
class SalaSelect extends Action
{
	var $tpl = "tpl/sala/tpl.SalaSelect.php";

	function inicializar()
	{		
		$this->asignar('redir', "?accion=".$_GET['redir']);
		$this->asignar('prefijoTitle', $_GET['prefijoTitle']);
	}
}
?>