<?
include_once BASE_DIR ."clases/listados/clase.Listado.php";
include_once BASE_DIR ."clases/dao/dao.Recoleccion.php";


class ListadoRecolecciones extends Listado
{
	function ListadoRecolecciones ($params)
	{
		parent::Listado();

		// Se carga la consulta para el listado
		$recoleccionDAO = new RecoleccionDAO();
		$sql = $recoleccionDAO->getSqlFindByParameters($params);

		// Configuracion
		$this->mensaje = PropertiesHelper::GetKey("recoleccion.busqueda.sin.resultados");
		$this->orden = "orden";
		$this->ex_pasaget = array("orden");
		$cfilas = "20";
		$maxpag = "5";
		$this->mostrar_total = true;
		$this->titulo_general= "";
		$this->mensaje_total = PropertiesHelper::GetKey("recoleccion.listado.total");
		$order_default = "nombre_recoleccion";
		$orden_tipo = "asc";
		$this->seleccionar_js = "";

		// Columnas
		$columnas["Nombre"] = array(
										"nombre" => "Recolecci&oacute;n",
			      	                   	"title" => "Nombre",
			          	                "dato_align" => "left",
			              	            "datos" => array("nombre_recoleccion"),
			                  	        "orden" => "nombre_recoleccion",
			                      	    "fijo" => false,
										"dato_width" => "70%"
									);
																								
		$columnas["Habilitada"] = array(
										"nombre" => "Habilitada",
										"title" => "Habilitada",			
										"fijo" => true,										
										"dato_align" => "center",				
										"dato_width" => "10%",
								   		"dato_boolean" => 'habilitado'	
									);

		$columnas["Editar"] = array(
										"nombre" => "Editar",
										"dato_title" => "Editar Recoleccion",
										"fijo" => true,
										"dato_href" => "#",
										"dato_href_parametro" => "id",
										"dato_onclick" => "editarRecoleccion(id)",
										"dato_align" => "center",				
										"dato_width" => "10%",
								   		"dato_foto" => "imagenes/editar.png");
		
		$columnas["Eliminar"] = array(
										"nombre" => "Eliminar",
										"dato_title" => "Eliminar recoleccion",
										"fijo" => true,
										"dato_href" => "#",
										"dato_href_parametro" => "id",
										"dato_onclick" => "eliminarRecoleccion(id)",
										"dato_align" => "center",				
										"dato_width" => "10%",
								   		"dato_foto" => "imagenes/eliminar.png");

		// Cargar parametros
		$this->datos($sql, $_GET, $columnas, $cfilas, $maxpag, $order_default, $orden_tipo);
	}
}
?>