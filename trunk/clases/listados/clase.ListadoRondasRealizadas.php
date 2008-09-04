<?
include_once BASE_DIR ."clases/listados/clase.Listado.php";
include_once BASE_DIR ."clases/dao/dao.LogRfid.php";  

class ListadoRondasRealizadas extends Listado
{
	function ListadoRondasRealizadas ($params)
	{
		parent::Listado();

		$this->mensaje = "La b&uacute;squeda no ha encontrado resultados";
				
		$this->orden = "orden";
		$this->ex_pasaget = array("orden");
		$this->mostrar_total = true;
		$this->titulo_general= "";
		$this->mensaje_total = 'Cantidad total de Salas visitadas: ';
		$this->seleccionar_js = "";
		
		$ronda = new LogRfidDAO();
		
		$sql = $ronda->getSqlRondasRealizadas($params);		
		$order_default = "codigo";
		$orden_tipo = "asc";
		$cfilas = "5";
		$maxpag = "5";

		// Columnas
        $columnas["Nombre"] = array("nombre" =>"Nombre",
                                  "title" =>"Nombre",
                                  "dato_align" =>"left",
                                  "datos" =>array("nombre"),
                                  "orden" =>"nombre",
                                  "fijo" =>false,
                                "dato_width" => "30%");
                                
        $columnas["Sala"] = array("nombre" =>"Sala",
                                  "title" =>"Sala",
                                  "dato_align" =>"left",
                                  "datos" =>array("sala"),
                                  "orden" =>"sala",
                                  "fijo" =>false,
                                "dato_width" => "15%");

		$columnas["Inicio"] = array("nombre" => "Inicio",
	      	                   	"title" =>"Inicio",
	          	                "dato_align" =>"left",
	              	            "datos" =>array("inicio"),
	                  	        "orden" =>"inicio",
	                      	    "fijo" =>false,
								"dato_width" => "15%");
		
        $columnas["Fin"] = array("nombre" => "Fin",
                                  "title" =>"Fin",
                                  "dato_align" =>"left",
                                  "datos" =>array("fin"),
                                  "orden" =>"fin",
                                  "fijo" =>false,
                                  "dato_width" => "15%");
								
								
		$columnas["Tarjeta"] = array("nombre" =>"Tarjeta",
		      	                "title" =>"Tarjeta",
		          	            "dato_align" =>"left",
		              	        "datos" =>array("tarjeta"),
		                  	    "orden" =>"tarjeta",
		                      	"fijo" =>false,
								"dato_width" => "30%");
								        

		// Agrego los datos del get para el paginado
		$params += $_GET;
		
		// Cargar parametros
		$this->datos($sql, $params, $columnas, $cfilas, $maxpag, $order_default, $orden_tipo);
	}
}
?>
