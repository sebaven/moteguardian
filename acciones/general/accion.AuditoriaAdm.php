<?
include_once BASE_DIR ."clases/listados/clase.Listados.php";
include_once BASE_DIR ."ionix/validators/DateField.php";

class AuditoriaAdm extends Action
{
	var $tpl = "tpl/auditoria/tpl.Auditoria.php";
	
	function validar(&$v) 
	{
		$v->add(new DateField('fecha_desde', 'fecha.desde.invalida'));
		$v->add(new DateField('fecha_hasta', 'fecha.hasta.invalida'));
	}

	function inicializar()
	{
		$this->asignar("titulo","Registros de Auditor&iacute;a");
		$this->asignar('options_usuarios', ComboUsuario());
		$this->asignar('options_acciones', ComboAccion());
	}

	function Buscar()
	{
		// Cargar Datos de la Busqueda
		$this->asignarArray($_GET);

		$listado = Listados::create('ListadoAuditoria', $_GET);
		$this->asignar("listado", $listado->imprimir_listado());
	}
}
?>