<?
include_once BASE_DIR ."clases/listados/clase.Listado.php";
include_once BASE_DIR ."clases/dao/dao.Envio.php";


class ListadoEnvios extends Listado
{
	function ListadoEnvios ($params) {
		parent::Listado();

		// Se carga la consulta para el listado
		$envioDAO = new EnvioDAO();
		$sql = $envioDAO->getSqlFindByParameters($params);

		// Configuracion
		$this->mensaje = PropertiesHelper::GetKey("recoleccion.busqueda.sin.resultados");
		$this->orden = "orden";
		$this->ex_pasaget = array("orden");
		$cfilas = "20";
		$maxpag = "5";
		$this->mostrar_total = true;
		$this->titulo_general= "";
		$this->mensaje_total = PropertiesHelper::GetKey("envio.listado.total");
		$order_default = "nombre_envio";
		$orden_tipo = "asc";
		$this->seleccionar_js = "";

		// Columnas
		$columnas["Nombre Envio"] = array(
										"nombre" => "Env&iacute;o",
			      	                   	"title" => "Nombre Env&iacute;o",
			          	                "dato_align" => "left",
			              	            "datos" => array("nombre_envio"),
			                  	        "orden" => "nombre_envio",
			                      	    "fijo" => false,
										"dato_width" => "30%"
									);
																								
		$columnas["Nombre Recoleccion"] = array(
										"nombre" => "Recolecci&oacute;n",
			      	                   	"title" => "Nombre Recolecci&oacute;n",
			          	                "dato_align" => "left",
			              	            "datos" => array("nombre_recoleccion"),
			                  	        "orden" => "nombre_recoleccion",
			                      	    "fijo" => false,
										"dato_width" => "30%"
									);

		$columnas["Inmediato"] = array(
										"nombre" => "Inmediato",
										"title" => "Inmediato",			
										"fijo" => true,										
										"dato_align" => "center",				
										"dato_width" => "10%",
								   		"dato_boolean" => 'inmediato'	
									);

		$columnas["Habilitado"] = array(
										"nombre" => "Habilitado",
										"title" => "Habilitado",			
										"fijo" => true,										
										"dato_align" => "center",				
										"dato_width" => "10%",
								   		"dato_boolean" => 'habilitado'	
									);

		$columnas["Editar"] = array(
										"nombre" => "Editar",
										"dato_title" => "Editar Env&iacute;o",
										"fijo" => true,
										"dato_href" => "#",
										"dato_href_parametro" => "id",
										"dato_onclick" => "editarEnvio(id)",
										"dato_align" => "center",				
										"dato_width" => "10%",
								   		"dato_foto" => "imagenes/editar.png");
		
		$columnas["Eliminar"] = array(
										"nombre" => "Eliminar",
										"dato_title" => "Eliminar envio",
										"fijo" => true,
										"dato_href" => "#",
										"dato_href_parametro" => "id",
										"dato_onclick" => "eliminarEnvio(id)",
										"dato_align" => "center",				
										"dato_width" => "10%",
								   		"dato_foto" => "imagenes/eliminar.png");

		// Cargar parametros
		$this->datos($sql, $_GET, $columnas, $cfilas, $maxpag, $order_default, $orden_tipo);
	}
}
?>