<?
// Este archivo contiene todas las funciones que se usan globalmente

function getFecha($date)
{
	$anio = substr($date, 0, 4);
	$mes = substr($date, 5, 2);
	$dia = substr($date, 8, 2);
	$nDia = substr($date, 11, 1);
	$nombreDia = nombreDia($nDia);
	$nombreMes = nombreMes($mes);
	
	return	nombreDia($nDia) . ", $dia $nombreMes de $anio";
}

function nombreDia($dia)
{
	switch ($dia)
	{
		case 0:	 return "Domingo";
		case 1:  return "Lunes";
		case 2:  return "Martes";
		case 3:  return "Mi&eacute;rcoles";
		case 4:  return "Jueves";		
		case 5:  return "Viernes";		
		case 6:  return "S&aacute;bado";
	}
}

function nombreMes($mes)
{
	switch ($mes)
	{
		case 1:	 return "Enero";
		case 2:  return "Febrero";
		case 3:  return "Marzo";
		case 4:  return "Abril";		
		case 5:  return "Mayo";		
		case 6:  return "Junio";		
		case 7:  return "Julio";		
		case 8:  return "Agosto";		
		case 9:  return "Septiembre";		
		case 10: return "Octubre";		
		case 11: return "Noviembre";		
		case 12: return "Diciembre";		
	}		
}

/**
 * Genera el script 'javascript' con el que se configura un 'jcalendar'.
 * EL script generado es el siguiente:
 * --------------------------------------
 *	<script type="text/javascript">
 *		Calendar.setup({
 *			inputField  : "$input",					// Id del campo al que se le seteara la fecha.
 *			ifFormat    : "%d/%m/%Y",				// Formato de la fecha.
 *			firstDay    : 0,						// Primer dia de la semana: 'domingo'.
 *			cache       : true,						// Que tenga una cache de calendarios.
 *			weekNumbers : false,					// Que no muestre el dia de la semana.
 *			range       : [2006,2011],				// Rango de anios a mostrar.
 *			button      : "$button"					// Id del boton que activa el calendario.
 *		});
 *	</script>
 * --------------------------------------
 * @param string $input		Elemento del formulario al que se le asignara la fecha seleccionada en el calendario.
 * @param string $button	Boton que activa/desactiva el calendario.
 * @return Un string conteniendo el script solicitado.
 */
function getCalendarDefinition($input, $button){
	$script = '<script type="text/javascript">';
	$script.= '	Calendar.setup({';
	$script.= '		inputField  : "'.$input.'",';
	$script.= '		ifFormat    : "%Y-%m-%d",';
	$script.= '		firstDay    : 0,';
	$script.= '		cache       : true,';
	$script.= '		weekNumbers : false,';
	$script.= '		range       : [2006,2011],';
	$script.= '		button      : "'.$button.'"';
	$script.= '	});';
	$script.= '</script>';
	
	return $script;
}


// Solo para TESTING
function printr($a) {
	echo "<pre>";
	print_r($a);
	echo "</pre>";
}

?>