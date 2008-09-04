<?
include_once BASE_DIR ."clases/listados/clase.Listado.php";
include_once BASE_DIR ."clases/dao/dao.Ronda.php";  

class ListadoRonda extends Listado
{
	function ListadoRonda ($params)
	{
		parent::Listado();

		$this->mensaje = "La b&uacute;squeda no ha encontrado resultados";
				
		$this->orden = "orden";
		$this->ex_pasaget = array("orden");
		$this->mostrar_total = false;
		$this->titulo_general= "";
		$this->mensaje_total = 'Cantidad total de Rondas: ';
		$this->seleccionar_js = "";
		
		$ronda = new RondaDAO();
		
		$sql = $ronda->getSqlRondas($params);		
		$order_default = "nombre_guardia";
		$orden_tipo = "asc";
		$cfilas = "50";
		$maxpag = "50";

		// Columnas
        $columnas["Nombre guardia"] = array("nombre" =>"Nombre del guardia",
                                  "title" =>"Nombre del guardia",
                                  "dato_align" =>"left",
                                  "datos" =>array("nombre_guardia"),
                                  "orden" =>"nombre_guardia",
                                  "fijo" =>false,
                                "dato_width" => "30%");
                                
        $columnas["Hora"] = array("nombre" =>"Hora",
        	                   	"title" =>"Hora",
            	                "dato_align" =>"left",
                	            "datos" =>array("hora"),
                    	        "orden" =>"hora",
                        	    "fijo" =>false,
								"dato_width" => "15%");

		$columnas["Dia Semanal"] = array("nombre" =>"Dia Semanal",
	      	                   	"title" =>"Dia Semanal",
	          	                "dato_align" =>"left",
	              	            "datos" =>array("dia_semana"),
	                  	        "orden" =>"dia_semana",
	                      	    "fijo" =>false,
								"dato_width" => "15%");
								
  		$columnas["Dia Absoluto"] = array("nombre" =>"Dia Absoluto",
	      	                   	"title" =>"Dia Absoluto",
	          	                "dato_align" =>"left",
	              	            "datos" =>array("dia_absoluto"),
	                  	        "orden" =>"dia_absoluto",
	                      	    "fijo" =>false,
								"dato_width" => "30%");

  		$columnas['Fecha Vigencia'] = array("nombre" =>"Fecha Vigencia",
	      	                   			"title" =>"Fecha Vigencia",
	          	                		"dato_align" =>"left",
	              	            		"datos" =>array("fecha_vigencia"),
	                  	        		"orden" =>"fecha_vigencia",
	                      	    		"fijo" =>false,
										"dato_width" => "30%");
  		
  		$columnas["Editar"]=	array("nombre"=>"Editar",
								"fijo"=>true,
								"dato_href"=>"#",
								"dato_href_parametro"=>"id",
								"dato_onclick" => "seleccionarRonda(id)",
								"dato_align"=>"center",				
								"dato_title"=>"Seleccionar Ronda",
								"dato_width" => "5%",
						   		"dato_foto"=>"imagenes/editar.png");		  									
				
		$columnas["Eliminar"]=	array("nombre"=>"Eliminar",
								"fijo"=>true,
								"dato_href"=>"#",
								"dato_href_parametro"=>"id",
								"dato_onclick" => "borrarRonda(id)",
								"dato_align"=>"center",				
								"dato_title"=>"Eliminar Ronda",
								"dato_width" => "5%",
						   		"dato_foto"=>"imagenes/eliminar.png");

		// Agrego los datos del get para el paginado
		$params += $_GET;
		
		// Cargar parametros
		$this->datos($sql, $params, $columnas, $cfilas, $maxpag, $order_default, $orden_tipo);
	}
}
?>
