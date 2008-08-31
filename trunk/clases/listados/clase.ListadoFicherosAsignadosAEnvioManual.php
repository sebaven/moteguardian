<?
include_once BASE_DIR ."clases/listados/clase.Listado.php";
include_once BASE_DIR ."clases/dao/dao.Tarea.php";

class ListadoFicherosAsignadosAEnvioManual extends Listado
{
	function ListadoFicherosAsignadosAEnvioManual ($params)
	{
		parent::Listado();

		// Se carga la consulta para el listado
		$tarea = new TareaDAO();
		$sql = $tarea->getSqlFindAsociadasEnvioManualByParameters($params);
		
		// Configuracion
		$this->mensaje = PropertiesHelper::GetKey("envioManual.conf.no.hay.ficheros.asignados");
		$this->orden = "orden";
		$this->ex_pasaget = array("orden");
		$cfilas = "5";
		$maxpag = "5";
		$this->mostrar_total = true;
		$this->titulo_general= "";
		$this->mensaje_total = PropertiesHelper::GetKey("envioManual.listado.total");
		$order_default = "nombre_original";
		$orden_tipo = "asc";
		$this->seleccionar_js = "";

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
		
		$columnas["Nombre Recolector"] = array("nombre" =>"Recoleccion",
		      	                "title" =>"Nombre Recolector",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("nombre_recolector"),
		                  	    "orden" =>"nombre_recolector",
		                      	"fijo" =>false,
								"dato_width" => "10%");
																						
		$columnas["Quitar"] = array(
								"nombre"=>"Quitar",
								"fijo"=>true,
								"dato_href"=>"#",
								"dato_href_parametro"=>"id",
								"dato_onclick" => "quitarFicheroFromEnvManual(id)",
								"dato_align"=>"center",				
								"dato_title"=>"Quitar Fichero",
								"dato_width" => "5%",
						   		"dato_foto"=>"imagenes/b_drop.png");
		
				
		$params += $_GET;	
		
		// Cargar parametros
		$this->datos($sql, $params, $columnas, $cfilas, $maxpag, $order_default, $orden_tipo);
	}
}
?>
