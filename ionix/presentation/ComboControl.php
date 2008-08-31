<?
/**
 * Representa un combo en HTML a partir de las opciones indicadas.
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
class ComboControl
{
	/**
	 * M�todo est�tico que imprime el combo en HTML con los par�metros especificados
	 * @access public
	 * @param $options array asociativo con los valores y los labels a mostrar
	 * @param $selected (string) indica el valor seleccionado en el combo
	 * @return void
	 */ 
	function Display($options, $selected = '')
	{
		if ($options)
		{
			 foreach ($options as $option => $label)
			 {
				print '<option value="' . htmlentities($option) . '"';
			
				if ($option == $selected)
				{
					print ' selected="selected"';
				}
			
				print '>' . htmlentities($label) . '</option>';
			}
		}
	}
	
	function GetHTML($options, $selected = '')
	{		
		$html = "";
		
		if ($options)
		{
			 foreach ($options as $option => $label)
			 {
				$html .= '<option value="' . htmlentities($option) . '"';
			
				if ($option == $selected)
				{
					$html .= ' selected="selected"';
				}
			
				$html .=  '>' . htmlentities($label) . '</option>';
			}
		}
		
		return $html;
	}	
	
}
?>