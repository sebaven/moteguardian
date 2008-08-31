<?
include_once BASE_DIR .'clases/util/clase.MailHelper.php';
include_once BASE_DIR .'clases/negocio/clase.Usuario.php';
include_once BASE_DIR .'clases/dao/dao.Trabajo.php';

class Aviso extends Action
{
	var $tpl = "tpl/tpl.Aviso.php";

	function diasHabilesEntre($fecha,$fechaLimite){
	
		$fecha = getdate($fecha);
		$fechaLimite = getdate($fechaLimite);
		$fecha = getdate(mktime(0,0,0,$fecha["mon"],$fecha["mday"],$fecha["year"]));

	
		$cantDias = 0;
		while ($fechaLimite[0] < $fecha[0]){
			if (($fechaLimite["wday"] != 0) && ($fechaLimite["wday"] != 6)) $cantDias++;
			$fechaLimite = getdate(strtotime("+1 day",mktime(0,0,0,$fechaLimite["mon"],$fechaLimite["mday"],$fechaLimite["year"])));
		}
		
		return $cantDias;
	}

	function inicializar()
	{
		$dom = new DomDocument();
		$dom->load(realpath(".") . "/config/emails.xml");
		$responsables = $dom->getElementsByTagName("responsable");
		
		foreach($responsables as $node){
   			$id_area = $node->attributes->item(0)->firstChild->data;
   			$emails_responsables = $node->getElementsByTagName("email");
   			$emails_resp = array();
	   			foreach ($emails_responsables as $em){
	   				$emails_resp[] = $em->firstChild->data;
	   			}
			$params = $this->_getEmailsUsuariosAviso($id_area);
			$params["to"] = implode(",", $emails_resp);

			$this->_enviarMailResponsable($params);
			$this->_enviarMailAviso($params["emails"]);
			
		}	
   		
		
	}
	
	/**
	 * Devuelve todos los emails de los usuarios activos que no 
	 * registraron trabajos por al menos dos d�as h�biles
	 * @author pfagalde
	 * @return (array) emails
	 */
	function _getEmailsUsuariosAviso($id_area)
	{
		$lista_emails = array();
		$r = new RecursoDAO();
		$trabajoDAO = new TrabajoDAO();
		$trabajo = new Trabajo();
		$area = new Area($id_area);
		
		// Obtengo todos los recursos activos
		$recursos = $r->filterBy("activo = '1' AND id_area = '$id_area'");
		// Para cada usuario verifico si lleva mas de dos dias h�biles 
		// de trabajo sin cargar y lo agrego a la lista
		$body.= "<table style='font:9pt Verdana;'>";
		$body.= "<thead>";
		$body.= "<tr bgcolor='#CCCCCC'>";
		$body.= "<th><b>Apellido y Nombre</b></th>";
		$body.= "<th><b>D�as h�biles desde la �ltima carga</b></th>";
		$body.= "</tr>";
		$body.= "</thead>";
		$body.= "<tbody>";
		foreach ($recursos as $r) 
		{
			$agregar = false;
			// Se pasan las fechas a timestamp para poder compararlas
			$hoy = strtotime(convertirFecha2YMD(date('d/m/Y')));
			$f = $r->getFechaUltimoTrabajo();
			if ($f != ""){
				$fechaUltimoTrabajo = strtotime(convertirFecha2YMD($f));
				// Se guarda el mail del recurso si hace mas de dos dias habiles que no
				// registra sus trabajos
				$dias = $this->diasHabilesEntre($hoy,$fechaUltimoTrabajo);
				if ($dias > 2) {
	                $lista_emails[] = trim($r->getEmail());
					$agregar = true;
	 			}
			} else {
				// Si no vuelve una fecha de trabajo, entonces nunca se cargaron trabajos
				$lista_emails[] = trim($r->getEmail());
				$agregar = true;
				$dias = "Nunca cargo horas";
			}
			
			if ($agregar){
				$body.= "<tr>";
				$body.= "<td>".$r->apellido." ".$r->nombre."</td>";
				$body.= "<td>".$dias."</td>";
				$body.= "</tr>";
			}
		}
		$body.= "</tbody>";
		$body.= "</table>";

		if (sizeof($lista_emails)>0){
			$params["TEMPLATE"] = "TEMPLATE_AVISO_RESPONSABLES";
			$params["subject"] = "Reporte de Carga de Horas";
			$params["emails"] = implode(",", $lista_emails);
			$params["body"] = $body;
			$params["area"] = $area->nombre;
		}
		
		return $params;
	}

	function _enviarMailResponsable($params){
        // No hay nadie que no haya cargado
        if($params["body"]=="") return;
		
        MailHelper::sendmail($params);
	}
	
	function _enviarMailAviso($emails)
	{
		$params['TEMPLATE'] = "TEMPLATE_AVISO";
		$params['subject'] = "Aviso de Kronos";
		$params['bcc'] = $emails;
		
		MailHelper::sendmail($params);
	}
}

?>