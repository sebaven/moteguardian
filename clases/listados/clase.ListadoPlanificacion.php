<?php
include_once BASE_DIR ."clases/listados/clase.Listado.php";
include_once BASE_DIR ."clases/dao/dao.Planificacion.php";

class ListadoPlanificacion extends Listado
{
	function ListadoPlanificacion ($params)
	{
		parent::Listado();
		$planificacionDAO = new PlanificacionDAO();
		
		// Cargar la consulta para el listado
		$sql = $planificacionDAO->getSqlPlanificaciones($params);

		// Configuracion		
		$this->mensaje = "No se ha agregado ninguna planificaci&oacute;n";
		$this->orden = "orden";
		$this->ex_pasaget = array("orden");
		$cfilas = "20";
		$maxpag = "5";
		$this->mostrar_total = false;
		$this->titulo_general= "";
		$this->mensaje_total = 'Total de Planificaciones: ';
		$order_default = "id";
		$orden_tipo = "asc";
		$this->seleccionar_js = "";

		// Columnas
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
								"dato_onclick" => "seleccionarPlanificacion(id)",
								"dato_align"=>"center",				
								"dato_title"=>"Seleccionar Planificacion",
								"dato_width" => "5%",
						   		"dato_foto"=>"imagenes/editar.png");		  									
				
		$columnas["Eliminar"]=	array("nombre"=>"Eliminar",
								"fijo"=>true,
								"dato_href"=>"#",
								"dato_href_parametro"=>"id",
								"dato_onclick" => "borrarPlanificacion(id)",
								"dato_align"=>"center",				
								"dato_title"=>"Eliminar Planificacion",
								"dato_width" => "5%",
						   		"dato_foto"=>"imagenes/eliminar.png");
			
		$vectorParche = $_GET;
		$vectorParche['id_ronda'] = $params;
		
		// Cargar parametros
		$this->datos($sql, $vectorParche, $columnas, $cfilas, $maxpag, $order_default, $orden_tipo);
	}
}
?>