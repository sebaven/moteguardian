<?php
include_once BASE_DIR ."clases/listados/clase.Listado.php";
include_once BASE_DIR ."clases/dao/dao.Actividad.php";

class ListadoActividades extends Listado
{
	function ListadoActividades ($params)
	{
		parent::Listado();
		$actividadDAO = new ActividadDAO();
		
		// Cargar la consulta para el listado
		$sql = $actividadDAO->getSqlFindByParameters($params);

		// Configuracion
		if($params['nombreCentral']||$params['nombreActividad']){
			$this->mensaje = "No se ha encontrado ninguna actividad que cumpla con las condiciones de b&uacute;squeda especificadas";
		} else {
			$this->mensaje = "No se ha configurado ninguna actividad";
		}
		$this->orden = "orden";
		$this->ex_pasaget = array("orden");
		$cfilas = "25";
		$maxpag = "5";
		$this->mostrar_total = true;
		$this->titulo_general= "";
		$this->mensaje_total = 'Total de Actividades: ';
		$order_default = "nombre_actividad";
		$orden_tipo = "asc";
		$this->seleccionar_js = "";

		// Columnas
		$columnas["Nombre Actividad"] = array("nombre" =>"Actividad",
	      	                   	"title" =>"Nombre Actividad",
	          	                "dato_align" =>"left",
	              	            "datos" =>array("nombre_actividad"),
	                  	        "orden" =>"nombre_actividad",
	                      	    "fijo" =>false,
								"dato_width" => "45%");
								
		$columnas["Nombre Plantilla"] = array("nombre" =>"Plantilla",
        	                   	"title" =>"Nombre Plantilla",
            	                "dato_align" =>"left",
                	            "datos" =>array("nombre_plantilla"),
                    	        "orden" =>"nombre_plantilla",
                        	    "fijo" =>false,
								"dato_width" => "45%");
				
		$columnas["editar"]=	array("nombre"=>"Editar",
								"fijo"=>true,
								"dato_href"=>"#",
								"dato_href_parametro"=>"id",
								"dato_onclick" => "seleccionarActividad(id)",
								"dato_align"=>"center",				
								"dato_title"=>"editar Actividad",
								"dato_width" => "5%",
						   		"dato_foto"=>"imagenes/editar.png");																		

		$columnas["Eliminar"]=	array("nombre"=>"Eliminar",
								"fijo"=>true,
								"dato_href"=>"#",
								"dato_href_parametro"=>"id",
								"dato_onclick" => "borrarActividad(id)",
								"dato_align"=>"center",				
								"dato_title"=>"Eliminar Actividad",
								"dato_width" => "5%",
						   		"dato_foto"=>"imagenes/eliminar.png");																		
																	
		// Cargar parametros
		$this->datos($sql, $_GET, $columnas, $cfilas, $maxpag, $order_default, $orden_tipo);
	}
}
?>