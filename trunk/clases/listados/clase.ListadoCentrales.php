<?
include_once BASE_DIR ."clases/listados/clase.Listado.php";
include_once BASE_DIR ."clases/dao/dao.Central.php";

class ListadoCentrales extends Listado
{
	function ListadoCentrales ($params)
	{
		parent::Listado();

		// Se carga la consulta para el listado
		$central = new CentralDAO();
		$sql = $central->getSqlFindByParameters($params);

		// Configuracion
		$this->mensaje = PropertiesHelper::GetKey("central.listado.mensaje");
		$this->orden = "orden";
		$this->ex_pasaget = array("orden");
		$cfilas = "20";
		$maxpag = "5";
		$this->mostrar_total = true;
		$this->titulo_general= "";
		$this->mensaje_total = PropertiesHelper::GetKey("central.listado.total");
		$order_default = "nombre";
		$orden_tipo = "asc";
		$this->seleccionar_js = "seleccionarCentral";

		// Columnas
		$columnas["Nombre"] = array("nombre" =>"Nombre",
	      	                   	"title" =>"Nombre",
	          	                "dato_align" =>"left",
	              	            "datos" =>array("nombre"),
	                  	        "orden" =>"nombre",
	                      	    "fijo" =>false,
								"dato_width" => "10%");
								
		$columnas["Codigo"] = array("nombre" =>"Codigo",
		      	                "title" =>"Codigo",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("codigo"),
		                  	    "orden" =>"codigo",
		                      	"fijo" =>false,
								"dato_width" => "10%");
								
		$columnas["Tecnologia"] = array("nombre" =>"Tecnologia",
		      	                "title" =>"Tecnolog&iacute;a",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("nombre_tecnologia"),
		                  	    "orden" =>"nombre_tecnologia",
		                      	"fijo" =>false,
								"dato_width" => "10%");

		$columnas["Eliminar"]=	array("nombre"=>"Eliminar",
								"fijo"=>true,
								"dato_href"=>"#",
								"dato_href_parametro"=>"id",
								"dato_onclick" => "borrarCentral(id)",
								"dato_align"=>"center",				
								"dato_title"=>"Eliminar Central",
								"dato_width" => "5%",
						   		"dato_foto"=>"imagenes/b_drop.png");																		
																	
		// Cargar parametros
		$this->datos($sql, $_GET, $columnas, $cfilas, $maxpag, $order_default, $orden_tipo);
	}
}
?>
