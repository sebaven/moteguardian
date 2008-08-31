<?
include_once BASE_DIR ."clases/negocio/clase.Log.php";
include_once BASE_DIR ."clases/dao/dao.Planilla.php";
include_once BASE_DIR ."clases/dao/dao.Partido.php";
include_once BASE_DIR ."clases/dao/dao.Letra.php";
include_once BASE_DIR ."clases/negocio/clase.Club.php";
include_once BASE_DIR ."clases/service/clase.Logger.php";
include_once BASE_DIR ."comun/defines_acciones_logger.php";
include_once BASE_DIR ."clases/util/clase.MailHelper.php";

/// Clase con metodos ESTATICOS para el envio de mails de notificacion
class MailService
{
	/// Envia un mail a la contraparte, avisando que la planilla esta disponible para ser validada.
	/// @public
	/// @param $params (array) 
	/// @return (bool) true si se envio el mail correctamente, false en caso contrario.
	function notificarHabilitacionValidar($params)
	{
		$planilla = new Planilla();
		$torneo = $params['torneo'];
		$rueda = $params['rueda'];
		$fecha = $params['numero_fecha'];
		$partido = $params['partido'];
		
		$HTMLTorneoFechaPartido = MailService::getHTMLTorneoFechaPartido($torneo, $rueda, $fecha, $partido);
		
		// Se obtiene la lista de emails del los usuarios del equipo perdedor o 
		// visitante en caso de empate
		$mails = $planilla->getMailsParaValidar($torneo, $rueda, $fecha, $partido);
		
		
		$body.="<br/><br/>";
		$body.= "La planilla con c&oacute;digo " .$torneo. "-" .$rueda. "-" .$fecha. "-" .$partido." fue marcada como Cargada Final y esta lista para ser validada.";
		$body.="<br/><br/>";
		
		$body.="Para acceder directamente a la planilla haga clic ";
		$body.="<a href='".DIRECCION_SITIO."/index.php?accion=planilla_vista_validar&torneo=$torneo&rueda=$rueda&numero_fecha=$fecha&partido=$partido'>aqu&iacute;</a>";
		$body.="<br/><br/>";
		
		$params["TEMPLATE"] = "TEMPLATE_AVISO_PROCESOS";
		$params["subject"] = "AAAHSC - Planilla " .$torneo. "-" .$rueda. "-" .$fecha. "-" .$partido. " habilitada para validacion";
		$params["to"] = $mails;
		
		$params["bcc"] = getBccProceso("AvisoValidacion");

		$params["body"] = $body;
		
		$this->asignar('body', $body);
		
		$ok_mail = MailHelper::sendmail($params);
		
		return $ok_mail;		
	}

	/// Envia un mail a la contraparte, avisando que la planilla esta disponible para ser validada.
	/// @public
	/// @param $torneo
	/// @param $rueda 
	/// @param $fecha 
	/// @param $partido 
	/// @return (HTML) bloque de datos HTML con Torneo Fecha y Partido para en envio de mails.
	function getHTMLTorneoFechaPartido($torneo, $rueda, $fecha, $partido)
	{
		$planilla = new Planilla();

		// Se obtienen los datos del partido
		$partidoDao = new PartidoDAO();
		$partido_jugado = $partidoDao->getPartido($torneo, $rueda, $fecha, $partido);

		// Se obtiene la fecha
		// Extraigo el año, mes y día de la fecha que trae la base
		list($ano,$mes,$dia) = sscanf($partido_jugado->Programado, "%d-%d-%d");
		$fecha_jugado = $dia . "-" . $mes . "-" . $ano;
		
		// Se obtienen los nombres de los equipos
		$Club = new ClubDAO();
		$club_local = $Club->getNombreById($partido_jugado->CLocal);
		$club_visitante = $Club->getNombreById($partido_jugado->CVisitante);
		
		// Se obtiene la letra de los equipos
		$letraDao = new LetraDAO();
		$letra_local = $letraDao->getById($partido_jugado->LLocal);
		$letra_visitante = $letraDao->getById($partido_jugado->LVisitante);
		
		// Se obtiene la planilla para mostrar el resultado del partido
		$planillaDao = new PlanillaDAO();
		$planilla = $planillaDao->getPlanilla($torneo, $rueda, $fecha, $partido);

		// Se obtiene el resultado del partido
		$goles_local = $planilla->goles_local;
		$goles_visitante = $planilla->goles_visitante;

		// Se obtiene el sector
		if ($partido_jugado->SectorID == CABALLEROS){
			$sector = "Caballeros";
		}
		else if ($partido_jugado->SectorID == DAMAS){
			$sector = "Damas";
		}

		// Cuerpo del mail HTML
		$body.= "<table style='font-size: 10pt; font-weight: bold;'><tr><td style='font-weight: normal;'>Torneo:</td>";
		$body.= "<td>" . $partido_jugado->Siglas . " - " . $sector . "</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
		$body.= "<tr><td style='font-weight: normal;'>Fecha:</td><td>" . $fecha_jugado . "</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
		$body.=	"<tr><td style='font-weight: normal;'>Partido:</td>";
		$body.= "<td>" . $club_local . " " . $letra_local->Nombre . "</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		$body.= "<td>" . $goles_local . "</td></tr>";
		$body.= "<tr><td>&nbsp;</td><td>" . $club_visitante . " " . $letra_visitante->Nombre . "</td><td>&nbsp;</td>";
		$body.= "<td>" . $goles_visitante . "</td></tr>";
		$body.= "</table>";
		
		return $body;
	}
	
	
	/// Envia información necesaria para el evio de mail de los procesos automaticos.
	/// @public
	/// @param $torneo
	/// @param $rueda 
	/// @param $fecha 
	/// @param $partido 
	/// @return Matriz con información necesaria para el envio de mails.
	function getInfoMail($torneo, $rueda, $fecha, $partido)
	{
		$planilla = new Planilla();

		// Se obtienen los datos del partido
		$partidoDao = new PartidoDAO();
		$partido_jugado = $partidoDao->getPartido($torneo, $rueda, $fecha, $partido);
		
		// Se obtiene la fecha
		// Extraigo el año, mes y día de la fecha que trae la base
		list($ano,$mes,$dia) = sscanf($partido_jugado->Programado, "%d-%d-%d");
		$fecha_jugado = $dia . "-" . $mes . "-" . $ano;
		
		
		// Se obtienen los nombres de los equipos
		$Club = new ClubDAO();
		$club_local = $Club->getNombreById($partido_jugado->CLocal);
		$club_visitante = $Club->getNombreById($partido_jugado->CVisitante);
		
		
		// Se obtiene la letra de los equipos
		$letraDao = new LetraDAO();
		$letra_local = $letraDao->getById($partido_jugado->LLocal);
		$letra_visitante = $letraDao->getById($partido_jugado->LVisitante);
		
		
		// Se obtiene la planilla para mostrar el resultado del partido
		$planillaDao = new PlanillaDAO();
		$planilla = $planillaDao->getPlanilla($torneo, $rueda, $fecha, $partido);

		// Se obtiene el resultado del partido
		$goles_local = $planilla->goles_local;
		$goles_visitante = $planilla->goles_visitante;
		
		
		// Se obtiene el sector
		if ($partido_jugado->SectorID == CABALLEROS){
			$sector = "Caballeros";
		}
		else if ($partido_jugado->SectorID == DAMAS){
			$sector = "Damas";
		}
		
		// Cargo el array con la información necesaria para el mail
		$infoMail = array();
		$infoMail = array(
							"torneo" => $partido_jugado->Siglas,
							"sector" => $sector,
							"fecha_jugado" => $fecha_jugado,
							"club_local" => $club_local,
							"club_visitante" => $club_visitante,
							"letra_local" => $letra_local->Nombre,
							"letra_visitante" => $letra_visitante->Nombre,
							"goles_local" => $goles_local,
							"goles_visitante" => $goles_visitante);
							
		return $infoMail;
	}
	
}
?>