<?
/**
 * Clase utilitaria para obtener keys de internacionalización con los mensajes más comunes
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
class MessageFactory {

	/**
	 * Devuelve una clave de insert o de error de acuerdo a la condición especificada
	 *
	 * @access public
	 * @param (bool) $ok condición a evaluar
	 * @return (string) key de internacionlización
	 */
	function InsertOrError($ok)
	{
		return ($ok) ? 'insert' : 'error';
	}


	/**
	 * Devuelve una clave de update o de error de acuerdo a la condición especificada
	 *
	 * @access public
	 * @param (bool) $ok condición a evaluar
	 * @return (string) key de internacionlización
	 */
	function UpdateOrError($ok)
	{
		return ($ok) ? 'update' : 'error';
	}
	
	/**
	 * Devuelve una clave entre dos de acuerdo a la condición especificada
	 *
	 * @access public
	 * @param (string) $ok
	 * @param (string) $message_ok Mensaje que se va a devolver si la condición es true
	 * @param (string) $message_error Mensaje que se va a devolver si la condición es falsa
	 * @return (string) key de internacionalización
	 */
	function OkOrError($ok, $key_ok, $key_error)
	{
		return ($ok) ? $key_ok : $key_error;
	}

	/**
	 * Devuelve un mensaje formateado de la clave especificada
	 *
	 * @access public
	 * @param (string) $key Key de internacionalización
	 * @param (bool) $error Si es true se devuelve el mensaje formateado para error
	 * @return unknown
	 */
/*	function getMensaje($key, $error = false)
	{
	   $m = PropertiesHelper::GetKey($key);	
	
	   if ($error)
	   {
	   		// Acá se puede refactorizar para que se devuelva como error
			$s =  $m;
	   }
	   else
	   {
			$s = $m;
	   }  
	   
	   return $s;
	}*/
	
	function getMensaje($key, $error = 0)
	{
	   $m = PropertiesHelper::GetKey($key);	
	
	   if ($error)
	   {
			$s = '<div class="msg_error">' . $m . '</div><br/>';
	   }
	   else
	   {
			$s = '<div class="msg_notify">' . $m . '</div><br/>';
	   }  
	   
	   return $s;
	}
}
?>