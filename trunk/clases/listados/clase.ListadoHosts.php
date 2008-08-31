<?
include_once BASE_DIR ."clases/listados/clase.Listado.php";
include_once BASE_DIR ."clases/dao/dao.Host.php";

class ListadoHosts extends Listado
{
	function ListadoHosts ($params)
	{
		parent::Listado();

		// Se carga la consulta para el listado
		$host = new HostDAO();
		$sql = $host->getSqlFindByParameters($params);

		// Configuracion
		$this->mensaje = PropertiesHelper::GetKey("host.listado.mensaje");
		$this->orden = "orden";
		$this->ex_pasaget = array("orden");
		$cfilas = "20";
		$maxpag = "5";
		$this->mostrar_total = true;
		$this->titulo_general= "";
		$this->mensaje_total = PropertiesHelper::GetKey("host.listado.total");
		$order_default = "nombre_host";
		$orden_tipo = "asc";
		$this->seleccionar_js = "";

		// Columnas
		$columnas["Nombre"] = array("nombre" =>"Host",
	      	                   	"title" =>"Nombre",
	          	                "dato_align" =>"left",
	              	            "datos" =>array("nombre_host"),
	                  	        "orden" =>"nombre_host",
	                      	    "fijo" =>false,
								"dato_width" => "10%");
								
		$columnas["Descripcion"] = array("nombre" =>"Descripci&oacute;n",
		      	                "title" =>"Descripci&oacute;n",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("descripcion"),
		                  	    "orden" =>"descripcion",
		                      	"fijo" =>false,
								"dato_width" => "10%");
								
		$columnas["TecnologiaEnvio"] = array("nombre" =>"Tecnolog&iacute;a de Env&iacute;o",
		      	                "title" =>"Tecnolog&iacute;a de Env&iacute;o",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("nombre_tecnologia"),
		                  	    "orden" =>"nombre_tecnologia",
		                      	"fijo" =>false,
								"dato_width" => "10%");
		
		$columnas["editar"]=	array("nombre"=>"Editar",
								"fijo"=>true,
								"dato_href"=>"#",
								"dato_href_parametro"=>"id",
								"dato_onclick" => "seleccionarHost(id)",
								"dato_align"=>"center",				
								"dato_title"=>"editar Host",
								"dato_width" => "5%",
						   		"dato_foto"=>"imagenes/editar.png");

		$columnas["Eliminar"]=	array("nombre"=>"Eliminar",
								"fijo"=>true,
								"dato_href"=>"#",
								"dato_href_parametro"=>"id",
								"dato_onclick" => "borrarHost(id)",
								"dato_align"=>"center",				
								"dato_title"=>"Eliminar Host",
								"dato_width" => "5%",
						   		"dato_foto"=>"imagenes/b_drop.png");																		
																	
		// Cargar parametros
		$this->datos($sql, $_GET, $columnas, $cfilas, $maxpag, $order_default, $orden_tipo);
	}
}
?>
