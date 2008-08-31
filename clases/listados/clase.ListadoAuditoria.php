<?  
	include_once BASE_DIR ."clases/negocio/clase.Log.php"; 
	include_once BASE_DIR ."clases/listados/clase.Listado.php"; 

class ListadoAuditoria extends Listado
{
	function ListadoAuditoria($array_config)
	{
		parent::Listado();

		// Cargar la consulta para el listado
		$log = new Log();
		$sql = $log->getSql($array_config);

		// Configuracion
		$this->mensaje = "No hay registros que cumplan con los datos especificados";		
		$this->orden = "";
		$this->mostrar_total = true;
		$this->ex_pasaget = array("orden1");
		$cfilas = "20";
		$maxpag = "5";
		$order_default = "fecha, hora";
		$orden_tipo = "DESC";

		// Columnas
		$columnas["Fecha"] = array("nombre" =>"Fecha",
        	                   	"title" =>"Fecha",
            	                "dato_align" =>"center",
                	            "datos" =>array("fecha_dmy"),
                    	        "orden" =>"fecha_dmy",
                        	    "fijo" =>true,
								"dato_width" => "10%");
								
		$columnas["Hora"] = array("nombre" =>"Hora",
        	                   	"title" =>"Hora",
            	                "dato_align" =>"center",
                	            "datos" =>array("hora"),
                    	        "orden" =>"hora",
                        	    "fijo" =>true,
								"dato_width" => "10%");

		$columnas["Usuario"] = array("nombre" =>"Usuario",
        	                   	"title" =>"Usuario",
            	                "dato_align" =>"center",
                	            "datos" =>array("usuario"),
                    	        "orden" =>"id_usuario",
                        	    "fijo" =>true,
								"dato_width" => "15%");

		$columnas["Accion"] = array("nombre" =>"Accion",
        	                   	"title" =>"Accion",
            	                "dato_align" =>"center",
                	            "datos" =>array("accion"),
                    	        "orden" =>"accion",
                        	    "fijo" =>true,
								"dato_width" => "20%");
								
		$columnas["Descripcion"] = array("nombre" =>"Descripcion",
        	                   	"title" =>"Descripcion",
            	                "dato_align" =>"center",
                	            "datos" =>array("descripcion"),
                    	        "orden" =>"descripcion",
                        	    "fijo" =>true,
								"dato_width" => "25%");
								

		// Cargar parametros
		$this->datos($sql, $_GET, $columnas, $cfilas, $maxpag, $order_default, $orden_tipo);		
	}	
}
?>