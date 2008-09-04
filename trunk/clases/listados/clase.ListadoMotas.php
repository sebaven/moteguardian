<?
include_once BASE_DIR ."clases/listados/clase.Listado.php";
include_once BASE_DIR ."clases/dao/dao.Dispositivo.php";  

class ListadoMotas extends Listado
{
	function ListadoMotas ($params)
	{
		parent::Listado();

		$this->mensaje = "No se han agregado motas a esta sala";
				
		$this->orden = "orden";
		$this->ex_pasaget = array("orden");
		$this->mostrar_total = false;
		$this->titulo_general= "";	
		$this->seleccionar_js = "";
		
		$dispositivo = new DispositivoDAO();
		
		$sql = $dispositivo->getSql($params);		
		$order_default = "codigo";
		$orden_tipo = "asc";
		$cfilas = "20";
		$maxpag = "20";

		// Columnas
		$columnas["Codigo"] = array("nombre" =>"Codigo",
	      	                   	"title" =>"C&oacute;digo",
	          	                "dato_align" =>"left",
	              	            "datos" =>array("codigo"),
	                  	        "orden" =>"codigo",
	                      	    "fijo" =>false,
								"dato_width" => "15%");
										
		$columnas["Descripcion"] = array("nombre" =>"Descripcion",
		      	                "title" =>"Descripci&oacute;n",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("descripcion"),
		                  	    "orden" =>"descripcion",
		                      	"fijo" =>false,
								"dato_width" => "80%");
								
		$columnas["Estado"] = array("nombre" =>"Estado",
		      	                "title" =>"Estado",
		          	            "dato_align" =>"left",
		              	        "dato_estado_dispositivo" => "estado",
		                  	    "orden" =>"estado",
		                      	"fijo" =>false,
								"dato_width" => "5%");


		// Agrego los datos del get para el paginado
		$params += $_GET;
		
		// Cargar parametros
		$this->datos($sql, $params, $columnas, $cfilas, $maxpag, $order_default, $orden_tipo);
	}
}
?>
