<?php
include_once BASE_DIR ."clases/listados/clase.Listado.php";
include_once BASE_DIR ."clases/dao/dao.Tarea.php";

class ListadoTareas extends Listado
{
	function ListadoTareas ($params)
	{
		parent::Listado();
		$tareaDAO = new TareaDAO();
		
		// Cargar la consulta para el listado
		$sql = $tareaDAO->getSqlEstadoTareas($params);  

		// Configuracion
		$this->mensaje = PropertiesHelper::GetKey("monitoreo.tarea.listado.mensaje");
		$this->orden = "orden";
		$this->ex_pasaget = array("orden");
		$cfilas = "25";
		$maxpag = "5";
		$this->mostrar_total = true;
		$this->titulo_general= "";
		$this->mensaje_total = PropertiesHelper::GetKey("monitoreo.tarea.listado.total");
		$order_default = "t.id";
		$orden_tipo = "asc";
		

		// Columnas
		$columnas["Nombre original de la tarea"] = array("nombre" =>"Nombre original de la tarea",
		      	                "title" =>"Nombre original de la tarea",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("nombre_original"),
		                  	    "orden" =>"nombre_original",
		                      	"fijo" =>true,
								"dato_width" => "22%");
		
		$columnas["Nombre actual de la tarea"] = array("nombre" =>"Nombre actual de la tarea",
	      	                   	"title" =>"Nombre actual de la tarea",
	          	                "dato_align" =>"left",
	              	            "datos" =>array("nombre_actual"),
	                  	        "orden" =>"nombre_actual",
	                      	    "fijo" =>true,
								"dato_width" => "23%");
								
		$columnas["Estado"] = array("nombre" =>"Estado",  
		      	                "title" =>"Estado",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("nombre_estado"),
		                  	    "orden" =>"nombre_estado",
		                      	"fijo" =>true,
								"dato_width" => "20%");

		$columnas["Instante de inicio"] = array("nombre" =>"Instante de inicio",
		      	                "title" =>"Instante de inicio",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("timestamp_ingreso"),
		                  	    "orden" =>"timestamp_ingreso",
		                      	"fijo" =>true,
								"dato_width" => "20%");
				
		$columnas["Exito"]=	array("nombre"=>"Exito",
								"fijo"=>true,
								"title" => "Exito",			
								"orden" => "fallo",
								"dato_align"=>"center",				
								"dato_width" => "5%",
						   		"dato_foto"=>'fallo');																			
																	
		// Cargar parametros
		$this->escapear_html = false;
		$this->datos($sql, $_POST, $columnas, $cfilas, $maxpag, $order_default, $orden_tipo);}
}
?>