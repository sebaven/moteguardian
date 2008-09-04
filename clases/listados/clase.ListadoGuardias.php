<?
include_once BASE_DIR ."clases/listados/clase.Listado.php";
include_once BASE_DIR ."clases/dao/dao.Guardia.php";  

class ListadoGuardias extends Listado
{
	function ListadoGuardias ($params)
	{
		parent::Listado();

		$this->mensaje = "La b&uacute;squeda no ha encontrado resultados";
				
		$this->orden = "orden";
		$this->ex_pasaget = array("orden");
		$this->mostrar_total = true;
		$this->titulo_general= "";
		$this->mensaje_total = 'Cantidad total de Guardias: ';
		$this->seleccionar_js = "";
		
		$guardiaDAO = new GuardiaDAO();
		
		$sql = $guardiaDAO->getSql($params);		
		$order_default = "nombre";
		$orden_tipo = "asc";
		$cfilas = "5";
		$maxpag = "5";

		// Columnas
		$columnas["Codigo de tarjeta"] = array("nombre" =>"Codigo de tarjeta",
	      	                   	"title" =>"C&oacute;digo de tarjeta",
	          	                "dato_align" =>"left",
	              	            "datos" =>array("codigo_tarjeta"),
	                  	        "orden" =>"codigo_tarjeta",
	                      	    "fijo" =>false,
								"dato_width" => "20%");
		
		$columnas["Nombre"] = array("nombre" =>"Nombre",
	      	                   	"title" =>"Nombre",
	          	                "dato_align" =>"left",
	              	            "datos" =>array("nombre"),
	                  	        "orden" =>"nombre",
	                      	    "fijo" =>false,
								"dato_width" => "35%");
								
		$columnas["Sala"] = array("nombre" =>"Sala",
		      	                "title" =>"Sala",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("usuario"),
		                  	    "orden" =>"usuario",
		                      	"fijo" =>false,
								"dato_width" => "35%");
        
		$columnas["editar"]=	array("nombre"=>"Editar",
								"fijo"=>true,
								"dato_href"=>"#",
								"dato_href_parametro"=>"id",
								"dato_onclick" => "seleccionarGuardia(id)",
								"dato_align"=>"center",				
								"dato_title"=>"editar guardia",
								"dato_width" => "5%",
						   		"dato_foto"=>"imagenes/editar.png");

		$columnas["Eliminar"]=	array("nombre"=>"Eliminar",
								"fijo"=>true,
								"dato_href"=>"#",
								"dato_href_parametro"=>"id",
								"dato_onclick" => "borrarGuardia(id)",
								"dato_align"=>"center",				
								"dato_title"=>"Eliminar guardia",
								"dato_width" => "5%",
						   		"dato_foto"=>"imagenes/eliminar.png");

		// Agrego los datos del get para el paginado
		$params += $_GET;
		
		// Cargar parametros
		$this->datos($sql, $params, $columnas, $cfilas, $maxpag, $order_default, $orden_tipo);
	}
}
?>
