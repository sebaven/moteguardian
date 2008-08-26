<?php
include_once BASE_DIR ."clases/listados/clase.Listado.php";
include_once BASE_DIR ."clases/dao/dao.Plantilla.php";

class ListadoFiltros extends Listado
{
	function ListadoFiltros ()
	{
		parent::Listado();
		$plantillaDAO = new PlantillaDAO();
		
		// Cargar la consulta para el listado
		$sql = $plantillaDAO->getSqlFiltros($_REQUEST['id_plantilla']); 

		// Configuracion
		$this->mensaje = "No se ha agregado ning&uacute;n filtro";
		$this->orden = "orden";
		$this->ex_pasaget = array("orden");
		$cfilas = "25";
		$maxpag = "5";
		$this->mostrar_total = true;
		$this->titulo_general= "";
		$this->mensaje_total = 'Total de filtros: ';
		$order_default = "id";
		$orden_tipo = "asc";
		$this->seleccionar_js = "seleccionarFiltro";

		// Columnas
		$columnas["TipoFiltro"] = array("nombre" =>"Tipo Filtro",
        	                   	"title" =>"Tipo Filtro",
            	                "dato_align" =>"left",
                	            "datos" =>array("tipo_filtro"),
                    	        "orden" =>"id_tipo_filtro",
                        	    "fijo" =>false,
								"dato_width" => "40%");

		$columnas["Nombre"] = array("nombre" =>"Nombre",
	      	                   	"title" =>"Nombre",
	          	                "dato_align" =>"left",
	              	            "datos" =>array("nombre"),
	                  	        "orden" =>"nombre",
	                      	    "fijo" =>false,
								"dato_width" => "20%");
				
		$columnas["Eliminar"]=	array("nombre"=>"Eliminar",
								"fijo"=>true,
								"dato_href"=>"#",
								"dato_href_parametro"=>"id",
								"dato_onclick" => "borrarNodoPlantilla(id)", //id del nodo_plantilla el filtro se borra en cascada!
								"dato_align"=>"center",				
								"dato_title"=>"Eliminar Filtro",
								"dato_width" => "5%",
						   		"dato_foto"=>"imagenes/b_drop.png");																		
																	
		// Cargar parametros		
		$this->datos($sql, $_GET, $columnas, $cfilas, $maxpag, $order_default, $orden_tipo);
	}
}
?>