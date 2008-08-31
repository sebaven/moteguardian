<?
/// Clase para el manejo de archivos HTML que forman uun template. Los parametros en el template se 
/// definen poniendolos entre # (numeral), ejemplo:  ##nombre-parametro##
/// usando distintos templates.
/// @version 1.0
/// @author Jorge Barbosa
class MailTemplate
{	
	/// Path por defecto donde se encuentran los templates
	/// @private
	/// @var string	
	var $path = 'mail_templates';
	
	/// Almacena el contenido del template cargado del archivo
	/// @private
	/// @var string
	var $template;

	/// Constructor de la clase. Carga un template de acuerdo al archivo pasado como parametro.
	/// @public
	/// @param $name (string) nombre del archivo a cargar
	/// @param $path (string) [opcional] path del cual se van a a cargar los templates
	function MailTemplate($name, $path = "")
	{
		// Setear el path de los templates
		if ($path)
		{
			$this->path = $path;
		}

		// Path y nombre del archivo a cargar
		$filename = $this->path . '/' . $name;
		
		// Cargar el contenido del archivo (template)
		if (file_exists($filename))
		{
			$fp = fopen($filename, 'r');
			if ($fp)
			{			
				$this->template = fread($fp, filesize ($filename));
				fclose($fp);
			}
		}
	}
	
	/// Cambia la ruta de busqueda de los templates.
	/// @public
	/// @param $path (string) ruta en la cual se van a buscar los templates.
	function setPath($path)
	{
		$this->path = $path;
	}

	/// Cambia los parametros del template cargado.
	/// @public
	/// @param $params (array asociativo) array que contiene como clave los distintos paramatros que existen el 
	/// template, los cuales se van a cambiar por los valores proporcionados.
	function setParams($params)
	{
		if (!$params)
		{
			return ;
		}
		
		// Reemplazar todos las marcas del template
		foreach ($params as $param => $value)
		{
			$this->template = str_replace('##' . $param . '##', nl2br($value), $this->template);
		}
	}
	
	/// Devuelve un string con el contenido del template en formato HTML.
	/// @public
	/// @return (string) contenido del template en formato HTML.
	function getTemplate()
	{
		return $this->template;
	}
}
?>