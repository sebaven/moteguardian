<?
/**
 * Clase que representa una página HTML que contiene variables que serán seteadas a la hora de utilizarla. Las variables
 * se especifican con los tags de PHP.
 * 
 * ----------------------------------------
 *      iOnix - PHP Framework - 2006
 * ----------------------------------------
 * Jorge Barbosa      <jmbarbosa@gmail.com>
 * Alejandro Molinari <amolinari@gmail.com>
 * ----------------------------------------
 * 
 * @version 1.3
 * @package presentation
 * @author jbarbosa
 * @author amolinari
 */ 
class Template
{
	/**
	 * Contiene el valor para cada una de las variables contenidas en la plantilla
	 * @access private
	 * @var array asociativo
	 */ 
	var $variables = array();

	/**
	 * Almacena todas las entradas con los datos.
	 * @access private
	 * @var string
	 */ 
	var $root = '';
	
	/**
	 * Constructor de la clase. Recibe como parámetro el directorio raíz desde donde se van a buscar las plantillas
	 * 
	 * @access public
	 * @param $root (string) path del directorio raíz
	 * @return void
	 */ 
	function Template($root = '')
	{
		$this->root = $root;
	}
	
	/**
	 * Asigna el valor $v a la variable $k
	 * 
	 * @access public
	 * @param $key (string) nombre de la variable
	 * @param $value (string/array) valor a asignar a la variable
	 * @return void
	 */ 
	function asignar($key, $value)
	{
		$this->variables[$key] = $value;
	}
	
	/**
	 * Muestra el contenido de la plantilla con las asignaciones de las variables realizadas
	 * 
	 * @access public
	 * @param $tpl (string) nombre de la plantilla
	 * @param $return (bool) especifica si se quiere devolver o no el contenido de la plantilla
	 * @return (string) si se especifica $return true, devuelve el contenido de la plantilla
	 */ 
	function mostrar($tpl, $return = false)
	{
		if (substr($tpl, 0, 1) == '/')
		{
			// Buscar desde la raíz...
			$tpl2 = $_SERVER['DOCUMENT_ROOT'] . $tpl;

			if (file_exists($tpl2))
			{
				$tpl = $tpl2;
			}
			else
			{
				$tpl = substr($tpl, 1);
			}
		}
		else
		{
			$tpl = $this->root . $tpl;
		}

		// Obtener las variables del documento
		extract($this->variables);

		if ($return)
		{
			ob_start();
		}

		require($tpl);

		if ($return)
		{
			$r = ob_get_contents();
			ob_end_clean();
			
			return $r;
		}
	}
}
?>