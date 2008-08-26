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
		$cfilas = "25";
		$maxpag = "5";
		$this->mostrar_total = true;
		$this->titulo_general= "";
		$this->mensaje_total = 'Total de Planificaciones: ';
		$order_default = "id";
		$orden_tipo = "asc";
		$this->seleccionar_js = "seleccionarPlanificacion";

		// Columnas
		$columnas["Hora"] = array("nombre" =>"Hora",
        	                   	"title" =>"Hora",
            	                "dato_align" =>"left",
                	            "datos" =>array("hora"),
                    	        "orden" =>"hora",
                        	    "fijo" =>false,
								"dato_width" => "40%");

		$columnas["Dia Semanal"] = array("nombre" =>"Dia Semanal",
	      	                   	"title" =>"Dia Semanal",
	          	                "dato_align" =>"left",
	              	            "datos" =>array("dia_semana"),
	                  	        "orden" =>"dia_semana",
	                      	    "fijo" =>false,
								"dato_width" => "20%");
								
  		$columnas["Dia Absoluto"] = array("nombre" =>"Dia Absoluto",
	      	                   	"title" =>"Dia Absoluto",
	          	                "dato_align" =>"left",
	              	            "datos" =>array("dia_absoluto"),
	                  	        "orden" =>"dia_absoluto",
	                      	    "fijo" =>false,
								"dato_width" => "20%");								
				
		$columnas["Eliminar"]=	array("nombre"=>"Eliminar",
								"fijo"=>true,
								"dato_href"=>"#",
								"dato_href_parametro"=>"id",
								"dato_onclick" => "borrarPlanificacion(id)",
								"dato_align"=>"center",				
								"dato_title"=>"Eliminar Planificacion",
								"dato_width" => "5%",
						   		"dato_foto"=>"imagenes/b_drop.png");																		
																	
		// Cargar parametros
		$this->datos($sql, $_GET, $columnas, $cfilas, $maxpag, $order_default, $orden_tipo);
	}
}
?>