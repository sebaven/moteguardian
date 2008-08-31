<?
include_once BASE_DIR ."clases/listados/clase.Listado.php";
include_once BASE_DIR ."clases/dao/dao.Recoleccion.php";

Class ListadoRecoleccionesConFallas extends Listado
{
	function ListadoRecoleccionesConFallas ($params)
	{
		parent::Listado();

		// Se carga la consulta para el listado
		$recoleccionDAO = new RecoleccionDAO();
		$sql = $recoleccionDAO->getSQLRecoleccionesConFallas($params);

		// Configuracion
		$this->mensaje = PropertiesHelper::GetKey("recoleccion.busqueda.sin.resultados");
		$this->orden = "orden";
		$this->ex_pasaget = array("orden");
		$cfilas = "20";
		$maxpag = "5";
		$this->mostrar_total = true;
		$this->titulo_general= "";
		$this->mensaje_total = PropertiesHelper::GetKey("envio.listado.total");
		$order_default = "fecha_de_ejecucion";
		$orden_tipo = "asc";
		$this->seleccionar_js = "";

		// Columnas
		// Columnas
		$columnas["Nombre"] = array(
										"nombre" => "Nombre",
			      	                   	"title" => "Nombre",
			          	                "dato_align" => "left",
			              	            "datos" => array("nombre"),
			                  	        "orden" => "nombre",
			                      	    "fijo" => false,
										"dato_width" => "30%"
									);																							
		
		$columnas["Fecha de ejecucion"] = array(
										"nombre" => "Fecha de ejecuci&oacute;n",
			      	                   	"title" => "Fecha de ejecuci&oacute;n",
			          	                "dato_align" => "left",
			              	            "datos" => array("fecha_de_ejecucion"),
			                  	        "orden" => "fecha_de_ejecucion",
			                      	    "fijo" => false,
										"dato_width" => "30%"
									);																							
		
		$columnas["Log"] = array(
										"nombre" => "Log",
										"title" => "Log",
										"dato_align" => "center",				
								   		"dato_foto_de_consulta" => "log",
										"dato_title" => "Log",
										"fijo" => true,											
										"dato_width" => "10%");
		
		$columnas["Editar"] = array(
										"nombre" => "Editar",
										"dato_title" => "Editar recolecci&oacute;n",
										"fijo" => true,
										"dato_href" => "#",
										"dato_href_parametro" => "id_recoleccion",
										"dato_onclick" => "editarRecoleccion(id_recoleccion)",
										"dato_align" => "center",				
										"dato_width" => "10%",
								   		"dato_foto" => "imagenes/editar.gif");
		
		$columnas["Cancelar tarea"] = array(
										"nombre" => "Cancelar Tarea",
										"dato_title" => "Cancelar Tarea",
										"fijo" => true,
										"dato_href" => "#",
										"dato_href_parametro" => "id",
										"dato_onclick" => "cancelarTarea(id)",
										"dato_align" => "center",				
										"dato_width" => "10%",
								   		"dato_foto" => "imagenes/stop.png");

		
		// Cargar parametros
		$this->datos($sql, $_GET, $columnas, $cfilas, $maxpag, $order_default, $orden_tipo);
	}
}
?>