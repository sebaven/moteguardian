<?
/**
 * Clase utilitaria con m�todos est�ticos de ayuda para la parte de presentaci�n
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
class PresentationUtil
{
	/**
	 * Devuelve un array para ser utilizado en la lista de un combo
	 * 
	 * @access public
	 * @param $list (array) objetos que contienen los campos id y el contenido en $field
	 * @param $field (string) nombre del campo que se mostrar� en las opciones del combo
	 * @param $first (bool) true para que aparezca la primera opci�n, false para que no
	 * @param $text (string) texto de la primera opci�n del combo
	 * @return (array) contiene el campo descripci�n de todos los registros de la tabla
	 */ 
	function getCombo($list, $field, $first = true, $text = 'Seleccione una opci�n')
	{
		// Agregar al arrray asociativo la opcion '' con valor cero
		if ($first)
			$options[0] = $text;
	
         
		foreach ($list as $item)
		{
			$options[$item->id] = $item->$field; 
		}

		return $options;
	}
}
?>