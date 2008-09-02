<?
include_once BASE_DIR ."clases/listados/clase.Listado.php";
include_once BASE_DIR ."clases/dao/dao.Dispositivo.php";  

class ListadoDispositivos extends Listado
{
	function ListadoDispositivos ($params)
	{
		parent::Listado();

		// Cargar la consulta para el listado
		$dispositivo = new DispositivoDAO();
		$sql = $dispositivo->getSql($params);
				
		if($params){
			$this->mensaje = "No existen usuarios que cumplan con las condiciones de búsqueda especificadas";
		} else {
			$this->mensaje = "No existen Usuarios";	
		}
		
		$this->orden = "orden";
		$this->ex_pasaget = array("orden");
		$cfilas = "5";
		$maxpag = "5";
		$this->mostrar_total = true;
		$this->titulo_general= "";
		$this->mensaje_total = 'Total de Dispositivos: ';
		$order_default = "codigo";
		$orden_tipo = "asc";
		$this->seleccionar_js = "";

		// Columnas
		$columnas["TipoDispositivo"] = array("nombre" =>"Tipo de Dispositivo",
        	                   	"title" =>"Tipo de Dispositivo",
            	                "dato_align" =>"left",
                	            "datos" =>array("tipo"),
                    	        "orden" =>"tipo",
                        	    "fijo" =>false,
								"dato_width" => "10%");

		$columnas["Codigo"] = array("nombre" =>"Codigo",
	      	                   	"title" =>"C&oacute;digo",
	          	                "dato_align" =>"left",
	              	            "datos" =>array("codigo"),
	                  	        "orden" =>"codigo",
	                      	    "fijo" =>false,
								"dato_width" => "10%");
								
		$columnas["Sala"] = array("nombre" =>"Sala",
		      	                "title" =>"Sala",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("sala"),
		                  	    "orden" =>"sala",
		                      	"fijo" =>false,
								"dato_width" => "10%");
								
		$columnas["Descripcion"] = array("nombre" =>"Descripcion",
		      	                "title" =>"Descripci&oacute;n",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("descripcion"),
		                  	    "orden" =>"descripcion",
		                      	"fijo" =>false,
								"dato_width" => "15%");
								
		$columnas["Estado"] = array("nombre" =>"Estado",
		      	                "title" =>"Estado",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("estado"),
		                  	    "orden" =>"estado",
		                      	"fijo" =>false,
								"dato_width" => "10%");

        
		$columnas["editar"]=	array("nombre"=>"Editar",
								"fijo"=>true,
								"dato_href"=>"#",
								"dato_href_parametro"=>"id",
								"dato_onclick" => "seleccionarDispositivo(id)",
								"dato_align"=>"center",				
								"dato_title"=>"editar Usuario",
								"dato_width" => "5%",
						   		"dato_foto"=>"imagenes/editar.png");

		$columnas["Eliminar"]=	array("nombre"=>"Eliminar",
								"fijo"=>true,
								"dato_href"=>"#",
								"dato_href_parametro"=>"id",
								"dato_onclick" => "borrarDispositivo(id)",
								"dato_align"=>"center",				
								"dato_title"=>"Eliminar Usuario",
								"dato_width" => "5%",
						   		"dato_foto"=>"imagenes/eliminar.png");
																	
		// Cargar parametros
		$this->datos($sql, $_GET, $columnas, $cfilas, $maxpag, $order_default, $orden_tipo);
	}
}
?>
