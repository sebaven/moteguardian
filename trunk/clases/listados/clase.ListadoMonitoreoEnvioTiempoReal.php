<?
include_once BASE_DIR ."clases/listados/clase.Listado.php";

class ListadoMonitoreoEnvioTiempoReal extends Listado
{
	function ListadoMonitoreoEnvioTiempoReal($params)
	{
		parent::Listado();

		// Cargar la consulta para el listado
		$tareaDAO = new tareaDAO();
		$sql = $tareaDAO->getSqlMonitoreoEnvioTiempoReal($params);
		
		$this->mensaje = PropertiesHelper::GetKey("monitoreo.envio.tiempo.real.no.result");
		$this->orden = "orden";
		$this->ex_pasaget = array("orden");
		$cfilas = "25";
		$maxpag = "5";
		$this->mostrar_total = true;
		$this->titulo_general= PropertiesHelper::GetKey("monitoreo.envio.tiempo.real.titulo.listado");
		$this->mensaje_total = PropertiesHelper::GetKey("monitoreo.envio.tiempo.real.total")." ";
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
								
		$columnas["Envio"] = array("nombre" =>"Env&iacute;o",
		      	                "title" =>"Env&iacute;o",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("ENVIO"),
		                  	    "orden" =>"ENVIO",
		                      	"fijo" =>false,
								"dato_width" => "10%");

		$columnas["Cantidad"] = array("nombre" =>"Cantidad",
		      	                "title" =>"Cantidad",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("CANTIDAD"),
		                  	    "orden" =>"CANTIDAD",
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
