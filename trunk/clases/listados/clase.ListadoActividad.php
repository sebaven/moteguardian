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
		$this->mensaje = "No se ha agregado ning�na actividad";
		$this->orden = "orden";
		$this->ex_pasaget = array("orden");
		$cfilas = "25";
		$maxpag = "5";
		$this->mostrar_total = true;
		$this->titulo_general= "";
		$this->mensaje_total = 'Total de Actividades: ';
		$order_default = "nombreActividad";
		$orden_tipo = "asc";
		$this->seleccionar_js = "seleccionarActividad";

		// Columnas
		$columnas["Nombre Actividad"] = array("nombre" =>"Nombre Actividad",
	      	                   	"title" =>"Nombre Actividad",
	          	                "dato_align" =>"left",
	              	            "datos" =>array("nombreActividad"),
	                  	        "orden" =>"nombreActividad",
	                      	    "fijo" =>false,
								"dato_width" => "20%");
								
		$columnas["Nombre Central"] = array("nombre" =>"Nombre Central",
        	                   	"title" =>"Nombre Central",
            	                "dato_align" =>"left",
                	            "datos" =>array("nombreCentral"),
                    	        "orden" =>"nombreCentral",
                        	    "fijo" =>false,
								"dato_width" => "40%");
								
		$columnas["Ejecutar Ahora"]=	array("nombre"=>"Ejecutar Ahora",
								"fijo"=>true,
								"dato_href"=>"#",
								"dato_href_parametro"=>"id",
								"dato_onclick" => "ejecutarActividadAhora(id)",
								"dato_align"=>"center",				
								"dato_title"=>"Ejecutar Actividad",
								"dato_width" => "5%",
						   		"dato_foto"=>"imagenes/listado/boton_fin.gif");
		
		$columnas["Eliminar"]=	array("nombre"=>"Eliminar",
								"fijo"=>true,
								"dato_href"=>"#",
								"dato_href_parametro"=>"id",
								"dato_onclick" => "borrarActividad(id)",
								"dato_align"=>"center",				
								"dato_title"=>"Eliminar Actividad",
								"dato_width" => "5%",
						   		"dato_foto"=>"imagenes/b_drop.png");																		
																	
		// Cargar parametros
		$this->datos($sql, $_GET, $columnas, $cfilas, $maxpag, $order_default, $orden_tipo);
	}
}
?>