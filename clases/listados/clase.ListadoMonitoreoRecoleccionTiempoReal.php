<?
include_once BASE_DIR ."clases/listados/clase.Listado.php";

class ListadoMonitoreoRecoleccionTiempoReal extends Listado
{
	function ListadoMonitoreoRecoleccionTiempoReal($params)
	{
		parent::Listado();

		// Cargar la consulta para el listado
		$tareaDAO = new tareaDAO();
		$sql = $tareaDAO->getSqlMonitoreoRecoleccionTiempoReal($params);
		
		$this->mensaje = PropertiesHelper::GetKey("monitoreo.recoleccion.tiempo.real.no.result");
		$this->orden = "orden";
		$this->ex_pasaget = array("orden");
		$cfilas = "25";
		$maxpag = "5";
		$this->mostrar_total = true;
		$this->titulo_general= PropertiesHelper::GetKey("monitoreo.recoleccion.tiempo.real.titulo.listado");
		$this->mensaje_total = PropertiesHelper::GetKey("monitoreo.recoleccion.tiempo.real.total")." ";
		$order_default = "r.id";
		$orden_tipo = "asc";
		$this->seleccionar_js = "";

		// Columnas
		$columnas["Actividad"] = array("nombre" =>"Actividad",
	      	                   	"title" =>"Actividad",
	          	                "dato_align" =>"left",
	              	            "datos" =>array("ACTIVIDAD"),
	                  	        "orden" =>"ACTIVIDAD",
	                      	    "fijo" =>false,
								"dato_width" => "10%");
								
		$columnas["Recoleccion"] = array("nombre" =>"Recolecci&oacute;n",
		      	                "title" =>"Recolecci&oacute;n",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("RECOLECCION"),
		                  	    "orden" =>"RECOLECCION",
		                      	"fijo" =>false,
								"dato_width" => "10%");
								
		$columnas["Total"] = array("nombre" =>"Total de la actividad",
		      	                "title" =>"Total de la actividad",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("CANT_TOTAL"),
		                  	    "orden" =>"CANT_TOTAL",
		                      	"fijo" =>false,
								"dato_width" => "15%");
								
		$columnas["Porcentaje"] = array("nombre" =>"Porcentaje",
		      	                "title" =>"Porcentaje",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("PORCENTAJE"),
		                  	    "orden" =>"PORCENTAJE",
		                      	"fijo" =>false,
								"dato_width" => "10%");
																	
		// Cargar parametros
		$this->datos($sql, $_GET, $columnas, $cfilas, $maxpag, $order_default, $orden_tipo);
	}
}
?>
