<?
/**
 * Colecci�n que administra una lista de errores, pudiendo obtener los resultados en formato HTML
 * 
 * ----------------------------------------
 *      iOnix - PHP Framework - 2006
 * ----------------------------------------
 * Jorge Barbosa      <jmbarbosa@gmail.com>
 * Alejandro Molinari <amolinari@gmail.com>
 * ----------------------------------------
 * 
 * @version 1.0
 * @package common
 * @author jbarbosa
 * @author amolinari
 */
class ErrorCollection
{
	/**
	 * Lista de errores
	 * @access private
	 * @var array 
	 */
	var $_errors;
	
	/**
	 * Constructor de la clase
	 *
	 * @access public
	 * @return ErrorCollection
	 */
	function ErrorCollection()
	{
	}
	
	/**
	 * Agrega un error a la colecci�n de errores
	 *
	 * @access public
	 * @param string $error
	 * @return void
	 */
	function add($error)
	{
		$this->_errors[] = $error;
	}
	
	/**
	 * Devuelve un array con todos los errores agregados
	 *
	 * @access public
	 * @return (array) lista de errores agregados
	 */
	function getErrors()
	{
		return $this->_errors;
	}
	
	/**
	 * Devuelve tags de HTML con la lista de errores. El formato del HTML es con tags '<li>' de una UL
	 * 
	 * @access public
	 * @return (string) HTML con la lista de errores, o un string vac�o si no hay errores
	 */
	function getHTMLErrors()
	{		
		if (! empty($this->_errors))
		{
			$html = '<font style="color:red;padding-left:4px;"size="-1">' . PropertiesHelper::GetKey('corregir_datos') ;
			$html .= '<br/>&bull;&nbsp;&nbsp;&nbsp;';
			$html .= implode('<br/>&bull;&nbsp;&nbsp;&nbsp;', $this->_errors);
			$html .= '</font>';
			$html .= '<br />';
			
			return $html;
		}
		
		return "";
	}
	
	/**
	 * Indica el estado de la colecci�n de errores
	 * 
	 * @access public
	 * @return (bool) true si no hay errores, false en caso contrario
	 */
	function isEmpty()
	{
		return (count($this->_errors) == 0);
	}
	
	/**
	 * Elimina todos los errores
	 *
	 * @access public
	 * @return void
	 */
	function clean()
	{
		$this->_errors = array();
	}
}
?>