<?
   include_once BASE_DIR ."clase.Mail.php"; 
   include_once BASE_DIR ."clase.MailTemplate.php";
   include_once BASE_DIR ."comun/def.mails.php";
?>
<?
/// Clase helper para la clase Mail. Contiene metodos ESTATICOS para envio de mails
/// usando el template correspondiente.
/// @version 1.0
/// @author amolinari
class MailHelper
{	
	/// Con los datos y el template cargado envia el mail.
	/// @public
	/// @param $params (array asociativo) contiene como clave un parametro y como valor el valor del parametro
	/// @return (bool) TRUE si pudo enviar el mail, o FALSE en caso contrario
	function sendMail($params)
	{
		// Cargar todas las constantes definidas
		$constants = get_defined_constants(); 

		// Obtener la direccion a la cual se va a enviar (TO)		
		$to = $params['to'];
		
		// Obtener la direccion a la cual se va a enviar (CC)		
		$cc = $params['cc'];
		
		// Obtener la direccion a la cual se va a enviar (BCC)
        $bcc = $params['bcc'];

		// Obtener el subject del mail
		$subject = $params['subject'];
		
		// Obtener el path del template
		$template = $constants[strtoupper($params['TEMPLATE'])];

		// Cargar parametros del mail	
		$mail = new Mail();
		$mail->from = MAIL_FROM;
		$mail->to = $to;
		$mail->cc = $cc;
		$mail->bcc = $bcc;
		$mail->subject = $subject;
		$mail->body = MailHelper::_getBody($template, $params);
		// Si el cuerpo excede cierta cantidad de caracteres, se codifica
		// en base64
		$mail->base64 = (strlen($mail->body) > CANT_CARACTERES_EMAIL);
		
		// Enviar mail
		return $mail->send();
	}

	/// Devuelve un string en formato HTML con el contenido del template indicado, con los valores pasados 
	/// en $params. Para cargar el template utiliza la clase MailTemplate, que lo carga de acuerdo al nombre
	/// del archivo.
	/// @private
	/// @param $name_template (string) nombre del archivo del template.
	/// @param $params (array asociativo) array con los datos a cargar en el template.
	/// @return (string) template en formato HTML.
	function _getBody($name_template, $params)
	{
		// Cargar Template y setear parametros
		$template = new MailTemplate($name_template, $path);
		
		// Cargar los parametros y obtener el cuerpo del mensaje
		$template->setParams($params);
		$body = $template->getTemplate();

		// Devolver string con el c�digo HTML con los par�metros seteados
		return $body;
	}
}
?>
