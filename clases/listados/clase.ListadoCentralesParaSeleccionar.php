<?
include_once BASE_DIR ."clases/listados/clase.Listado.php";
include_once BASE_DIR ."clases/dao/dao.Central.php";

class ListadoCentralesParaSeleccionar extends Listado
{
	function ListadoCentralesParaSeleccionar ($params)
	{
		parent::Listado();

		// Se carga la consulta para el listado
		$central = new CentralDAO();
		$sql = $central->getSqlFindCentralesParaSeleccionarByParameters($params);

		// Configuracion
		$this->mensaje = PropertiesHelper::GetKey("central.listado.seleccion");
		$this->orden = "orden";
		$this->ex_pasaget = array("orden");
		$cfilas = "5";
		$maxpag = "5";
		$this->mostrar_total = true;
		$this->titulo_general= "";
		$this->mensaje_total = PropertiesHelper::GetKey("central.listado.total");
		$order_default = "c.nombre";
		$orden_tipo = "asc";
		$this->seleccionar_js = "";
		$this->id=1;

		// Columnas
		$columnas["Nombre"] = array("nombre" =>"Central",
	      	                   	"title" =>"Nombre",
	          	                "dato_align" =>"left",
	              	            "datos" =>array("nombre_central"),
	                  	        "orden" =>"nombre_central",
	                      	    "fijo" =>false,
								"dato_width" => "10%");
								
		$columnas["Descripcion"] = array("nombre" =>"Descripcion",
		      	                "title" =>"Descripcion",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("descripcion_central"),
		                  	    "orden" =>"descripcion_central",
		                      	"fijo" =>false,
								"dato_width" => "10%");

		$columnas["Procesador"] = array("nombre" =>"Procesador",
		      	                "title" =>"Procesador",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("procesador"),
		                  	    "orden" =>"procesador",
		                      	"fijo" =>false,
								"dato_width" => "10%");
								
		$columnas["Tecnologia"] = array("nombre" =>"Tecnolog&iacute;a",
		      	                "title" =>"Tecnolog&iacute;a",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("nombre_tecnologia_central"),
		                  	    "orden" =>"nombre_tecnologia_central",
		                      	"fijo" =>false,
								"dato_width" => "10%");

		$columnas["Tecnologia Recoleccion"] = array("nombre" =>"Tecnolog&iacute;a Recolecci&oacute;n",
		      	                "title" =>"Tecnolog&iacute;a Recolecci&oacute;n",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("nombre_tecnologia"),
		                  	    "orden" =>"nombre_tecnologia",
		                      	"fijo" =>false,
								"dato_width" => "10%");
																						
		$columnas["Seleccionar"] = array(
								"nombre"=>"Seleccionar",
								"fijo"=>true,								
								"dato_align"=>"center",				
								"dato_title"=>"Seleccionar",
								"dato_width" => "5%",
						   		"dato_checkbox" => "id");

		$params['accion'] = $_GET['accion'];
		$params['id_recoleccion'] = $_GET['id_recoleccion'];
		$params['offset'.$this->id] = $_GET['offset'.$this->id];
		$params['btnBuscar'] = 'Buscar';
		$params['id_recoleccion_manual'] = $_GET['id_recoleccion_manual'];
		
		// Cargar parametros
		$this->datos($sql, $params, $columnas, $cfilas, $maxpag, $order_default, $orden_tipo);
	}
}