<?
include_once BASE_DIR ."clases/listados/clase.Listado.php";

class ListadoUsuarios extends Listado
{
	function ListadoUsuarios ($params)
	{
		parent::Listado();

		// Cargar la consulta para el listado
		$usuario = new UsuarioDAO();
		$sql = $usuario->getSql($params);
				
		if($params){
			$this->mensaje = "No existen usuarios que cumplan con las condiciones de búsqueda especificadas";
		} else {
			$this->mensaje = "No existen Usuarios";	
		}
		
		$this->orden = "orden";
		$this->ex_pasaget = array("orden");
		$cfilas = "25";
		$maxpag = "5";
		$this->mostrar_total = true;
		$this->titulo_general= "";
		$this->mensaje_total = 'Total de Usuarios: ';
		$order_default = "usuario";
		$orden_tipo = "asc";
		$this->seleccionar_js = "seleccionarUsuario";

		// Columnas
		$columnas["Usuario"] = array("nombre" =>"Usuario",
        	                   	"title" =>"Usuario",
            	                "dato_align" =>"left",
                	            "datos" =>array("usuario"),
                    	        "orden" =>"usuario",
                        	    "fijo" =>false,
								"dato_width" => "10%");

		$columnas["Nombre"] = array("nombre" =>"Nombre",
	      	                   	"title" =>"Nombre",
	          	                "dato_align" =>"left",
	              	            "datos" =>array("nombre"),
	                  	        "orden" =>"nombre",
	                      	    "fijo" =>false,
								"dato_width" => "10%");
								
		$columnas["Apellido"] = array("nombre" =>"Apellido",
		      	                "title" =>"Apellido",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("apellido"),
		                  	    "orden" =>"apellido",
		                      	"fijo" =>false,
								"dato_width" => "10%");
								
		$columnas["Email"] = array("nombre" =>"Email",
		      	                "title" =>"Email",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("email"),
		                  	    "orden" =>"email",
		                      	"fijo" =>false,
								"dato_width" => "15%");
								
		$columnas["Telefono1"] = array("nombre" =>"Telefono1",
		      	                "title" =>"Telefono 1",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("telefono1"),
		                  	    "orden" =>"telefono1",
		                      	"fijo" =>false,
								"dato_width" => "10%");

		$columnas["Telefono2"] = array("nombre" =>"Telefono2",
		      	                "title" =>"Telefono2",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("telefono2"),
		                  	    "orden" =>"telefono2",
		                      	"fijo" =>false,
								"dato_width" => "10%");
								
		$columnas["Rol"] = array("nombre" =>"Rol",
        	                   	"title" =>"Rol",
            	                "dato_align" =>"left",
                	            "datos" =>array("rol"),
                    	        "orden" =>"rol",
                        	    "fijo" =>false,
								"dato_width" => "5%");	

		$columnas["Eliminar"]=	array("nombre"=>"Eliminar",
								"fijo"=>true,
								"dato_href"=>"#",
								"dato_href_parametro"=>"id",
								"dato_onclick" => "borrarUsuario(id)",
								"dato_align"=>"center",				
								"dato_title"=>"Eliminar Usuario",
								"dato_width" => "5%",
						   		"dato_foto"=>"imagenes/b_drop.png");																		
																	
		// Cargar parametros
		$this->datos($sql, $_GET, $columnas, $cfilas, $maxpag, $order_default, $orden_tipo);
	}
}
?>
