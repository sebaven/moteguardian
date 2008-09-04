<?
include_once BASE_DIR ."clases/listados/clase.Listado.php";
include_once BASE_DIR ."clases/dao/dao.LogAlarma.php";  

class ListadoAlarmas extends Listado
{
	function ListadoAlarmas($params)
	{
		parent::Listado();

		$this->mensaje = "La b&uacute;squeda no ha encontrado resultados";
				
		$this->orden = "orden";
		$this->ex_pasaget = array("orden");
		$this->mostrar_total = false;
		$this->titulo_general= "";
		$this->mensaje_total = 'Cantidad total de Salas visitadas: ';
		$this->seleccionar_js = "";
		
		$alarma = new LogAlarmaDAO();
		
		$sql = $alarma->getSql($params);		
		$order_default = "inicio";
		$orden_tipo = "asc";
		$cfilas = "50";
		$maxpag = "50";

		// Columnas
        $columnas["Disparador"] = array("nombre" =>"Dispositivo Disparador",
                                  "title" =>"Dispositivo Disparador",
                                  "dato_align" =>"left",
                                  "datos" =>array("codigo"),
                                  "orden" =>"codigo",
                                  "fijo" =>false,
                                "dato_width" => "30%");
                                
        $columnas["Falsa"] = array("nombre" =>"Es Falsa",
                                  "title" =>"Es Falsa",
                                  "dato_align" =>"left",
                                  "datos" =>array("falsa"),
                                  "orden" =>"falsa",
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
								
								
		// Agrego los datos del get para el paginado
		$params += $_GET;
		
		// Cargar parametros
		$this->datos($sql, $params, $columnas, $cfilas, $maxpag, $order_default, $orden_tipo);
	}
}
?>
