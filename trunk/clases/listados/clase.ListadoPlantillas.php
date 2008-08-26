<?php
include_once BASE_DIR ."clases/listados/clase.Listado.php";
include_once BASE_DIR ."clases/dao/dao.Plantilla.php";

class ListadoPlantillas extends Listado
{
	function ListadoPlantillas ($params)
	{
		parent::Listado();
		$plantillaDAO = new PlantillaDAO();
		
		// Cargar la consulta para el listado
		$sql = $plantillaDAO->getSqlPlantillas($params); 

		// Configuracion
		if($params){
			$this->mensaje = "No se ha encontrado ninguna plantilla que cumpla con las condiciones de busqueda especificadas";
		} else {
			$this->mensaje = "No se ha configurado ninguna plantilla";
		}
		$this->orden = "orden";
		$this->ex_pasaget = array("orden");
		$cfilas = "25";
		$maxpag = "5";
		$this->mostrar_total = true;
		$this->titulo_general= "";
		$this->mensaje_total = 'Total de plantillas: ';
		$order_default = "id";
		$orden_tipo = "asc";
		$this->seleccionar_js = "seleccionarPlantilla";

		// Columnas
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
								"dato_onclick" => "borrarPlantilla(id)", 
								"dato_align"=>"center",				
								"dato_title"=>"Eliminar Plantilla",
								"dato_width" => "5%",
						   		"dato_foto"=>"imagenes/b_drop.png");																		
																	
		// Cargar parametros		
		$this->datos($sql, $_GET, $columnas, $cfilas, $maxpag, $order_default, $orden_tipo);
	}
}
?>