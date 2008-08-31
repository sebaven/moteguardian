<?
/// Clase para el manejo de mails. Permite mandar mails en formato HTML o texto plano.
/// @version 1.0
/// @author Jorge Barbosa
class Mail
{
   	/// Direccion/es a la/s cual/es se va/n a enviar el/los mail/s.
	/// @public
	/// @var string
	var $to;
	
   	/// Copia a la/s cual/es se va/n a enviar el/los mail/s.
	/// @public
	/// @var string
    var $cc;

   	/// Copia oculta a la/s cual/es se va/n a enviar el/los mail/s.
	/// @public
	/// @var string
    var $bcc;

   	/// Direccion de origen del mail.
	/// @public
	/// @var string
	var $from;
	
   	/// Subject del mail.
	/// @public
	/// @var string
    var $subject;

   	/// Indica si el contenido del mail es HTML o texto plano.
	/// @public
	/// @var bool
	var $html;
	
  	/// Subject del mail.
	/// @private
	/// @var string	
    var $headers;

    /// Si el email va codificado en base64
    var $base64;
    
	/// Constructor de la clase; setea todas las propiedades a null.
	/// @public
    function Mail() 
    {
        $this->to       = NULL;
        $this->cc       = NULL;
        $this->bcc      = NULL;
        $this->subject  = NULL;
        $this->from     = NULL;
        $this->headers  = NULL;  
        $this->html     = TRUE;
    }

	/// Envia el mail con los parametros preestablecidos.
	/// @public
	/// @return (bool) TRUE si pudo enviar el mail, o FALSE en caso contrario
    function send() 
    {
   		global $error;
   		
  		$this->_setHeaders();
		$error->_handleError(E_WARNING,$this->to . "||" . $this->subject . "||" . $this->headers,'archivo',0010);
		
		if ($this->base64 == true)
  			$this->body = rtrim(chunk_split(base64_encode($this->body)));
		
		
        if(mail($this->to, $this->subject, $this->body, $this->headers))
		{
			return TRUE;
		}
        else
		{
			return FALSE;
		}
    }
		
	/// Arma el contenido del mail, de acuerdo si es con formato o sin el.
	/// @private
    function _setHeaders() 
    {
        $this->headers = "From: $this->from\r\n";
        
        $this->headers.= "MIME-Version: 1.0\r\n";
        $this->headers.= "Content-type: text/html; charset=iso-8859-1;\r\n";

        if($this->base64 == true)
            $this->headers.= "Content-Transfer-Encoding: base64;\r\n";
        
		if(!empty($this->cc))
		{
			$this->headers.= "Cc: $this->cc\r\n";
		}

        if(!empty($this->bcc))
		{
			$this->headers.= "Bcc: $this->bcc\r\n";
		}
    }
}

?>
