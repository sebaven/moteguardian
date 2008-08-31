<?
include_once BASE_DIR ."clases/listados/clase.Listado.php";
include_once BASE_DIR ."clases/dao/dao.Tarea.php";

class ListadoFicherosParaSeleccionar extends Listado
{
	function ListadoFicherosParaSeleccionar ($params)
	{
		parent::Listado();

		// Se carga la consulta para el listado
		$tarea = new TareaDAO();
		$sql = $tarea->getSqlFindFicherosParaSeleccionarByParameters($params);

		// Configuracion
		$this->mensaje = PropertiesHelper::GetKey("ficherosParaSeleccionar.listado.seleccion");
		$this->orden = "orden";
		$this->ex_pasaget = array("orden");
		$cfilas = "5";
		$maxpag = "5";
		$this->mostrar_total = true;
		$this->titulo_general= "";
		$this->mensaje_total = PropertiesHelper::GetKey("ficherosParaSeleccionar.listado.total");
		$order_default = "nombre_original";
		$orden_tipo = "asc";
		$this->seleccionar_js = "";
		$this->id = 1;

		// Columnas
		$columnas["Nombre Original"] = array("nombre" =>"Nombre Original",
	      	                   	"title" =>"Nombre Original",
	          	                "dato_align" =>"left",
	              	            "datos" =>array("nombre_original"),
	                  	        "orden" =>"nombre_original",
	                      	    "fijo" =>false,
								"dato_width" => "10%");
								
		$columnas["Nombre Central"] = array("nombre" =>"Central",
		      	                "title" =>"Nombre Central",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("nombre_central"),
		                  	    "orden" =>"nombre_central",
		                      	"fijo" =>false,
								"dato_width" => "10%");

		$columnas["Procesador"] = array("nombre" =>"Procesador",
		      	                "title" =>"Procesador",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("procesador"),
		                  	    "orden" =>"procesador",
		                      	"fijo" =>false,
								"dato_width" => "10%");
								
		$columnas["Tecnologia Central"] = array("nombre" =>"Tecnolog&iacute;a Central",
		      	                "title" =>"Tecnolog&iacute;a Central",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("nombre_tecnologia_central"),
		                  	    "orden" =>"nombre_tecnologia_central",
		                      	"fijo" =>false,
								"dato_width" => "10%");

		$columnas["Tecnologia Recoleccion"] = array("nombre" =>"Tecnolog&iacute;a Recolecci&oacute;n",
		      	                "title" =>"Tecnolog&iacute;a Recolecci&oacute;n",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("nombre_tecnologia"),
		                  	    "orden" =>"nombre_tecnologia",
		                      	"fijo" =>false,
								"dato_width" => "10%");
		
		$columnas["Nombre Recolector"] = array("nombre" =>"Recolecci&oacute;n",
		      	                "title" =>"Nombre Recolector",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("nombre_recolector"),
		                  	    "orden" =>"nombre_recolector",
		                      	"fijo" =>false,
								"dato_width" => "10%");
																						
		$columnas["Seleccionar"] = array(
								"nombre"=>"Seleccionar",
								"fijo"=>true,								
								"dato_align"=>"center",				
								"dato_title"=>"Seleccionar",
								"dato_width" => "5%",
						   		"dato_checkbox" => "id");
		
		$params += $_GET;				
		
		// Cargar parametros		
		$this->datos($sql, $params, $columnas, $cfilas, $maxpag, $order_default, $orden_tipo);
	}
}