<?
include_once BASE_DIR ."clases/listados/clase.Listado.php";

class ListadoEstTotalFicheros extends Listado
{
	function ListadoEstTotalFicheros($params)
	{
		parent::Listado();

		// Cargar la consulta para el listado
		$tareaDAO = new TareaDAO();
		$sql = $tareaDAO->getSqlEstadisticaTotalFicherosEnviados($params);
		
		
		$this->mensaje = PropertiesHelper::GetKey("est.total.ficheros.no.result");
		$this->orden = "orden";
		$this->ex_pasaget = array("orden");
		$cfilas = "25";
		$maxpag = "5";
		$this->mostrar_total = true;
		$this->titulo_general= PropertiesHelper::GetKey("est.total.ficheros.titulo.listado");
		$this->mensaje_total = PropertiesHelper::GetKey("est.total.ficheros.total")." ";
		$order_default = "r.id";
		$orden_tipo = "asc";
		$this->seleccionar_js = "";

		// Columnas
		$columnas["Central"] = array("nombre" =>"Central",
	      	                   	"title" =>"Central",
	          	                "dato_align" =>"left",
	              	            "datos" =>array("CENTRAL"),
	                  	        "orden" =>"CENTRAL",
	                      	    "fijo" =>false,
								"dato_width" => "10%");
								
		$columnas["Fecha"] = array("nombre" =>"Fecha",
		      	                "title" =>"Fecha",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("FECHA"),
		                  	    "orden" =>"FECHA",
		                      	"fijo" =>false,
								"dato_width" => "10%");
								
		$columnas["Tecnologia"] = array("nombre" =>"Tecnolog&iacute;a",
		      	                "title" =>"Tecnolog&iacute;a",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("TECNOLOGIA"),
		                  	    "orden" =>"TECNOLOGIA",
		                      	"fijo" =>false,
								"dato_width" => "15%");
		
		$columnas["Destino"] = array("nombre" =>"Destino",
		      	                "title" =>"Destino",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("DESTINO"),
		                  	    "orden" =>"DESTINO",
		                      	"fijo" =>false,
								"dato_width" => "15%");
		
		$columnas["Cantidad"] = array("nombre" =>"Cantidad",
		      	                "title" =>"Cantidad",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("CANTIDAD"),
		                  	    "orden" =>"CANTIDAD",
		                      	"fijo" =>false,
								"dato_width" => "10%");
																	
		// Cargar parametros
		$this->datos($sql, $_GET, $columnas, $cfilas, $maxpag, $order_default, $orden_tipo);
	}
}
?>
