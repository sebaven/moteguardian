<?
/**
 * Chequea la validez de un Email. 
 * 
 * //podria poner las cosas del mail
 *  
 * @author mhernandez
 */
class EmailValidator extends Validator {
	var $_email;

	/**
	 * Mediante este constructor se indica el email a validar.
	 * @param string $email Campo del formulario que tiene el email a validar. 
	 * @return EmailValidator
	 * @access public
	 */
	function EmailValidator ($email, $key = '') {
		parent::Validator($email, $key);
		$this->_email = $email;
	}


	/**
	 * @return TRUE sólo si el email es válido
	 */
	function isValid() {
		
	$mail_correcto = false;
    $email = $this->_email;
	
    //se comprueban cosas básicas que componene a un mail
	if ((strlen($email) >= 6) && (substr_count($email,"@") == 1) && (substr($email,0,1) != "@") && (substr($email,strlen($email)-1,1) != "@"))
		{
       if ((!strstr($email,"'")) && (!strstr($email,"\"")) && (!strstr($email,"\\")) && (!strstr($email,"\$")) && (!strstr($email," "))) 
       		{
          	//se comprueba que contenga el caracter .
          	if (substr_count($email,".")>= 1)
          		{
             	//se obtiene la terminación del dominio
             	$term_dom = substr(strrchr ($email, '.'),1);
             	
             	//se comprueba que la terminación del dominio sea correcta
             	if (strlen($term_dom)>1 && strlen($term_dom)<5 && (!strstr($term_dom,"@")) )
             		{
	                //se compruebo que lo que se encuentra antes del dominio sea correcto
	                $antes_dom = substr($email,0,strlen($email) - strlen($term_dom) - 1);
	                $caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1);
	                if ($caracter_ult != "@" && $caracter_ult != ".")
                		{
                   		$mail_correcto = true;
                		}
             		}
          		}
       		}
    	}
    
    return($mail_correcto);
        
	}
}
?>