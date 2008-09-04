<?php
include_once BASE_DIR ."clases/listados/clase.Listado.php";
include_once BASE_DIR ."clases/dao/dao.ItemRonda.php";

class ListadoItemsRonda extends Listado
{
	function ListadoItemsRonda($params)
	{
		parent::Listado();
		$itemRondaDAO = new ItemRondaDAO();
		
		// Cargar la consulta para el listado
		$sql = $itemRondaDAO->getSqlItemRonda($params);

		// Configuracion		
		$this->mensaje = "A&uacute;n no se ha agregado ning&uacute;n eslab&oacute;n a la ronda";
		$this->orden = "orden";
		$this->ex_pasaget = array("orden");
		$cfilas = "20";
		$maxpag = "5";
		$this->mostrar_total = false;
		$this->titulo_general= "";
		$this->mensaje_total = 'Total de Eslabones: ';
		$order_default = "orden";
		$orden_tipo = "asc";
		$this->seleccionar_js = "";

		// Columnas
		$columnas["Orden"] = array("nombre" =>"Orden",
        	                   	"title" =>"Orden",
            	                "dato_align" =>"left",
                	            "datos" =>array("orden"),
                    	        "orden" =>"orden",
                        	    "fijo" =>false,
								"dato_width" => "10%");

		$columnas["Sala"] = array("nombre" =>"Sala",
        	                   	"title" =>"Orden",
            	                "dato_align" =>"left",
                	            "datos" =>array("sala"),
                    	        "orden" =>"sala",
                        	    "fijo" =>false,
								"dato_width" => "40%");
		
		$columnas["Total minutos duración"] = array("nombre" =>"Duraci&oacute;n (minutos)",
        	                   	"title" =>"Total minutos duraci&oacute;n",
            	                "dato_align" =>"left",
                	            "datos" =>array("duracion"),
                    	        "orden" =>"duracion",
                        	    "fijo" =>false,
								"dato_width" => "30%");
		  		
		$columnas["Editar"]=	array("nombre"=>"Editar",
								"fijo"=>true,
								"dato_href"=>"#",
								"dato_href_parametro"=>"id",
								"dato_onclick" => "seleccionarItemRonda(id)",
								"dato_align"=>"center",				
								"dato_title"=>"Seleccionar Eslab&oacute;n",
								"dato_width" => "5%",
						   		"dato_foto"=>"imagenes/editar.png");		  									
				
		$columnas["Eliminar"]=	array("nombre"=>"Eliminar",
								"fijo"=>true,
								"dato_href"=>"#",
								"dato_href_parametro"=>"id",
								"dato_onclick" => "borrarItemRonda(id)",
								"dato_align"=>"center",				
								"dato_title"=>"Eliminar Eslab&oacute;n",
								"dato_width" => "5%",
						   		"dato_foto"=>"imagenes/eliminar.png");
			
		$vectorParche = $_GET;
		$vectorParche['id_ronda'] = $params;
		
		// Cargar parametros
		$this->datos($sql, $vectorParche, $columnas, $cfilas, $maxpag, $order_default, $orden_tipo);
	}
}
?>