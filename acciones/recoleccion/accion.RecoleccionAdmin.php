<?
include_once BASE_DIR ."clases/listados/clase.Listados.php";

/**
 * @author cgalli
 */
class RecoleccionAdmin extends Action
{
	var $tpl = "tpl/recoleccion/tpl.RecoleccionAdmin.php";	
	
	function inicializar() {
		// Cargo los combos				
		$this->asignar("tecnologias_central", ComboTecnologiaCentral(true,""));
		$this->asignar("tecnologias_recolector", ComboTecnologiaRecoleccion(true,""));
		
		$this->asignar("nombre_recoleccion", $_GET['nombre_recoleccion']);
		$this->asignar("nombre_central", $_GET['nombre_central']);
		$this->asignar("procesador", $_GET['procesador']);
		$this->asignar("id_tecnologia_central", $_GET["tecnologia_central"]);
		$this->asignar("id_tecnologia_recolector", $_GET["tecnologia_recolector"]);
		if($_GET['solo_habilitadas']) $this->asignar("solo_habilitadas_checked", "checked");		
	}

	function limpiar(){
	}
	
	function buscar() {	
		$listado = Listados::create('ListadoRecolecciones', $_GET);		
		$this->asignar("listado", $listado->imprimir_listado());
	}
}
?>