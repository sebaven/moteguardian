<?
/**
 * Representa un combo de selección de fechas en HTML a partir de las opciones indicadas.
 *
 * ----------------------------------------
 *      iOnix - PHP Framework - 2006
 * ----------------------------------------
 * Jorge Barbosa      <jmbarbosa@gmail.com>
 * Alejandro Molinari <amolinari@gmail.com>
 * ----------------------------------------
 * 
 * @version 1.0
 * @package presentation
 * @author jbarbosa
 * @author amolinari
 */ 
class DateControl
{
	/**
	 * Imprime el combo de selección de dias
	 * @access public
	 * @param $day_selected (int) nro. de día seleccionado por defecto
	 * @param $head (int) 1 si va a existir un primer elemento en blanco, 0 (cero) en caso contrario
	 * @return void
	 */ 
	function Day($day_selected = 0, $head = 1)
	{
		// Agregar el item al inicio del combo
		if ($head)
		{
			print '<option value="0"></option>';
		}
	
		for ($i = 1; $i <= 31; $i++)
		{
			print '<option value="' . $i . '"';
	
			if ($i == $day_selected)
			{
				print ' selected="selected"';
			}
	
			print '>' . str_pad($i, 2, '0', STR_PAD_LEFT) . '</option>';
		}		
	}
	
	/**
	 * Imprime el combo de selección de meses
	 * @access public
	 * @param $month_selected (int) nro. de mes que va a estar seleccionado por defecto
	 * @param $head (int) 1 si va a existir un primer elemento en blanco, 0 (cero) en caso contrario
	 * @return void
	 */ 
	function Month($month_selected = 0, $head = 1)
	{
		// Agregar el item al inicio del combo
		if ($head)
		{
			print '<option value="0"></option>';
		}
	
		for ($i = 1; $i <= 12; $i++)
		{
			print '<option value="' . $i . '"';
	
			if ($i == $month_selected)
			{
				print ' selected="selected"';
			}
	
			print '>' . str_pad($i, 2, '0', STR_PAD_LEFT) . '</option>';
		}		
	}
	
	/**
	 * Imprime el combo de selección de años
	 * @access public
	 * @param $year_selected (int) nro. de año que va a estar seleccionado por defecto
	 * @param $year_ini (int) Año a partir del cual se va a mostrar el combo. -1 indica desde el año actual menos 3
	 * @param $year_end (int) Año final que se va a mostrar en el combo. -1 indica desde el año actual más 8
	 * @param $head (int) 1 si va a existir un primer elemento en blanco, 0 (cero) en caso contrario
	 * @return void
	 */ 
	function Year($year_selected = 0, $year_ini = -1, $year_end = -1, $head = 1)
	{
		// Agregar el item al inicio del combo
		if ($head)
		{
			print '<option value="0"></option>';
		}
	
		// Configuar los anios del combo
		if ($year_ini == -1)
		{			
			$year_ini = date("Y") - 3;
		}
		if ($year_end == -1)
		{
			$year_end = $year_ini + 8;
		}
	
		for ($i = $year_ini; $i <= $year_end; $i++)
		{
			print '<option value="' . $i . '"';
	
			if ($i == $year_selected)
			{
				print ' selected="selected"';
			}
	
			print '>' . $i . '</option>';
		}		
	}
}
?>