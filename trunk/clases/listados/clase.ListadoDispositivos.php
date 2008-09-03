<?
include_once BASE_DIR ."clases/listados/clase.Listado.php";
include_once BASE_DIR ."clases/dao/dao.Dispositivo.php";  

class ListadoDispositivos extends Listado
{
	function ListadoDispositivos ($params)
	{
		parent::Listado();

		$this->mensaje = "La b&uacute;squeda no ha encontrado resultados";
				
		$this->orden = "orden";
		$this->ex_pasaget = array("orden");
		$this->mostrar_total = true;
		$this->titulo_general= "";
		$this->mensaje_total = 'Cantidad total de Dispositivos: ';
		$this->seleccionar_js = "";
		
		$dispositivo = new DispositivoDAO();
		
		$sql = $dispositivo->getSql($params);		
		$order_default = "codigo";
		$orden_tipo = "asc";
		$cfilas = "5";
		$maxpag = "5";

		// Columnas
		$columnas["TipoDispositivo"] = array("nombre" =>"Tipo de Dispositivo",
        	                   	"title" =>"Tipo de Dispositivo",
            	                "dato_align" =>"left",
                    	        "orden" => "tipo",
                        	    "fijo" =>false,
                	            "dato_tipo_dispositivo" => "tipo",
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
		              	        "datos" =>array("id_sala"),
		                  	    "orden" =>"id_sala",
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
		              	        "dato_estado_dispositivo" => "estado",
		                  	    "orden" =>"estado",
		                      	"fijo" =>false,
								"dato_width" => "10%");

        
		$columnas["editar"]=	array("nombre"=>"Editar",
								"fijo"=>true,
								"dato_href"=>"#",
								"dato_href_parametro"=>"id",
								"dato_onclick" => "seleccionarDispositivo(id)",
								"dato_align"=>"center",				
								"dato_title"=>"editar dispositivo",
								"dato_width" => "5%",
						   		"dato_foto"=>"imagenes/editar.png");

		$columnas["Eliminar"]=	array("nombre"=>"Eliminar",
								"fijo"=>true,
								"dato_href"=>"#",
								"dato_href_parametro"=>"id",
								"dato_onclick" => "borrarDispositivo(id)",
								"dato_align"=>"center",				
								"dato_title"=>"Eliminar dispositivo",
								"dato_width" => "5%",
						   		"dato_foto"=>"imagenes/eliminar.png");

		// Agrego los datos del get para el paginado
		$params += $_GET;
		
		// Cargar parametros
		$this->datos($sql, $params, $columnas, $cfilas, $maxpag, $order_default, $orden_tipo);
	}
}
?>
