<?
include_once BASE_DIR ."clases/listados/clase.Listado.php";
include_once BASE_DIR ."clases/dao/dao.TecnologiaCentral.php";

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
		$cfilas = "5";
		$maxpag = "5";
		$this->mostrar_total = true;
		$this->titulo_general= "";
		$this->mensaje_total = PropertiesHelper::GetKey("central.listado.total");
		$order_default = "nombre_central";
		$orden_tipo = "asc";
		$this->seleccionar_js = "";

		// Columnas
		$columnas["Nombre"] = array("nombre" =>"Central",
	      	                   	"title" =>"Nombre",
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
								
		$columnas["TecnologiaRecoleccion"] = array("nombre" =>"Tecnologia de Recolecci&oacute;n",
		      	                "title" =>"Tecnolog&iacute;a de Recolecci&oacute;n",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("nombre_tecnologia"),
		                  	    "orden" =>"nombre_tecnologia",
		                      	"fijo" =>false,
								"dato_width" => "10%");
		
		$columnas["TecnologiaCentral"] = array("nombre" =>"Tecnologia de Central",
		      	                "title" =>"Tecnolog&iacute;a de Central",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("nombre_tecnologia_central"),
		                  	    "orden" =>"nombre_tecnologia_central",
		                      	"fijo" =>false,
								"dato_width" => "10%");
		
		$columnas["editar"]=	array("nombre"=>"Editar",
								"fijo"=>true,
								"dato_href"=>"#",
								"dato_href_parametro"=>"id",
								"dato_onclick" => "seleccionarCentral(id)",
								"dato_align"=>"center",				
								"dato_title"=>"editar Central",
								"dato_width" => "5%",
						   		"dato_foto"=>"imagenes/editar.png");

		$columnas["Eliminar"]=	array("nombre"=>"Eliminar",
								"fijo"=>true,
								"dato_href"=>"#",
								"dato_href_parametro"=>"id",
								"dato_onclick" => "borrarCentral(id)",
								"dato_align"=>"center",				
								"dato_title"=>"Eliminar Central",
								"dato_width" => "5%",
						   		"dato_foto"=>"imagenes/eliminar.png");																		
																	
		// Cargar parametros
		$this->datos($sql, $params, $columnas, $cfilas, $maxpag, $order_default, $orden_tipo);
	}
}
?>
