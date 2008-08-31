<?
include_once BASE_DIR ."clases/listados/clase.Listado.php";
include_once BASE_DIR ."clases/dao/dao.Central.php";
class ListadoTecnologiaCentral extends Listado
{
	function ListadoTecnologiaCentral ($params)
	{
		parent::Listado();

		// Se carga la consulta para el listado
		$tecnologiaCentralDAO = new TecnologiaCentralDAO();
		$sql = $tecnologiaCentralDAO->getSQLGetAll();

		// Configuracion
		$this->mensaje = PropertiesHelper::GetKey("tecnologia.central.listado.mensaje");
		$this->orden = "orden";
		$this->ex_pasaget = array("orden");
		$cfilas = "5";
		$maxpag = "5";
		$this->mostrar_total = true;
		$this->titulo_general= "";
		$this->mensaje_total = PropertiesHelper::GetKey("tecnologia.central.listado.total");
		$order_default = "nombre";
		$orden_tipo = "asc";

		// Columnas
		$columnas["nombre"] = array(
								"nombre" =>"Tecnolog&iacute;a",
	      	                   	"title" =>"Nombre",
	          	                "dato_align" =>"left",
	              	            "datos" =>array("nombre"),
	                  	        "orden" =>"nombre",
	                      	    "fijo" =>false,
								"dato_width" => "20%"
									);
								
		$columnas["descripcion"] = array(
								"nombre" =>"Descripci&oacute;n",
		      	                "title" =>"Descripci&oacute;n",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("descripcion"),
		                  	    "orden" =>"descripcion",
		                      	"fijo" =>false,
								"dato_width" => "65%"
									);
								
		$columnas["version"] = array(
								"nombre" =>"Versi&oacute;n",
		      	                "title" =>"Versi&oacute;n",
		          	            "dato_align" =>"center",
		              	        "datos" =>array("version"),
		                  	    "orden" =>"version",
		                      	"fijo" =>false,
								"dato_width" => "5%"
									);
		
		$columnas["editar"]=	array(
								"nombre"=>"Editar",
								"fijo"=>true,
								"dato_href"=>"#",
								"dato_href_parametro"=>"id",
								"dato_onclick" => "editarTecnologiaCentral(id)",
								"dato_align"=>"center",				
								"dato_title"=>"Editar",
								"dato_width" => "5%",
						   		"dato_foto"=>"imagenes/editar.png"
									);
		
		$columnas["eliminar"]=	array("nombre"=>"Eliminar",
								"fijo"=>true,
								"dato_href"=>"#",
								"dato_href_parametro"=>"id",
								"dato_onclick" => "borrarTecnologiaCentral(id)",
								"dato_align"=>"center",				
								"dato_title"=>"Eliminar Central",
								"dato_width" => "5%",
						   		"dato_foto"=>"imagenes/eliminar.png",
						   			);
																				
																	
		// Cargar parametros
		$this->datos($sql, $_GET, $columnas, $cfilas, $maxpag, $order_default, $orden_tipo);
	}
}

?>
