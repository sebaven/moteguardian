<?

	define("BASE_DIR_RELATIVO","../");
	define("BASE_DIR",realpath(BASE_DIR_RELATIVO)."/");

	include_once(BASE_DIR."comun/defines_app.php");
	include_once(BASE_DIR."ionix/config/ionix-config.php");
	include_once(BASE_DIR."ionix/data/Db.php"); 
	include_once(BASE_DIR."ionix/data/DbMySql.php"); 
	include_once(BASE_DIR."ionix/data/ConnectionManager.php");
	include_once(BASE_DIR."ionix/data/AbstractEntity.php");
	include_once(BASE_DIR."ionix/data/AbstractDAO.php");

	// Obtener una conexión a la base
	$db = ConnectionManager::getConnection('');

	$mensaje_resultado='';
	$mensaje_error='';
	if (!empty($_GET['idRFID']) && isset($_GET['idRFID'])) {
		if (!empty($_GET['enviar_estado']) && isset($_GET['enviar_estado'])) {
			if ($_GET['estado'] == CONST_EN_USO) { // ENCENDER
				// Se actualiza el estado del Lector RFID
				$str_sql = 'UPDATE `dispositivo` SET `estado`=\'' . CONST_EN_USO . '\' WHERE `id`=\''.$_GET['idRFID'].'\' LIMIT 1;';
				$db->leer($str_sql);
				$mensaje_resultado.='Operación de encendido de Lector RFID "' . $_GET['idRFID'] . '" recibida<br/>';
			} else if ($_GET['estado'] == CONST_APAGADO) { // APAGAR
				// Se actualiza el estado del Lector RFID
				$str_sql = 'UPDATE `dispositivo` SET `estado`=\'' . CONST_APAGADO . '\' WHERE `id`=\''.$_GET['idRFID'].'\' LIMIT 1;';
				$db->leer($str_sql);
				$mensaje_resultado.='Operación de apagado de Lector RFID "' . $_GET['idRFID'] . '" recibida<br/>';
			} else {
				$mensaje_error.='ERROR: La operación recibida es inválida<br/>';
			}
		}
		else if (!empty($_GET['enviar_evento']) && isset($_GET['enviar_evento'])) {
			if (!empty($_GET['lectura']) && isset($_GET['lectura'])) {
				if ($_GET['evento'] == '1') { // DETECCIÓN
					// Se inserta la nueva detección en la tabla log_rfid
					$str_sql = 'INSERT INTO `log_rfid` (`id_rfid` , `timestamp_inicio`, `codigo_tarjeta`) VALUES (\''.$_GET['idRFID'].'\', NOW( ), \''.$_GET['lectura'].'\');';
					$db->leer($str_sql);
					$mensaje_resultado.='Notificación de detección de Lector RFID "' . $_GET['idRFID'] . '" recibida<br/>';
				} else if ($_GET['evento'] == '0') { // FIN DETECCIÓN
					// Se escribe el timestamp_fin de la última detección no finalizada de este Lector RFID
					// IMPORTANTE: En la consulta interviene una tabla intermedia 'aux_log_rfid' como workaround a un problema al seleccionar desde la tabla a actualizar. Extraído de: http://www.xaprb.com/blog/2006/06/23/how-to-select-from-an-update-target-in-mysql/
					$str_sql = 'UPDATE `log_rfid` SET `timestamp_fin`=NOW( ) WHERE `id_rfid`=\''.$_GET['idRFID'].'\' AND `codigo_tarjeta` = \''.$_GET['lectura'].'\' AND `timestamp_fin` IS NULL AND `timestamp_inicio`=(SELECT ti FROM (SELECT MAX(`timestamp_inicio`) AS ti FROM `log_rfid` WHERE `id_rfid`=\''.$_GET['idRFID'].'\' AND `codigo_tarjeta` = \''.$_GET['lectura'].'\') AS aux_log_rfid) LIMIT 1;';
					$db->leer($str_sql);
					$mensaje_resultado.='Notificación de fin de detección de Lector RFID "' . $_GET['idRFID'] . '" recibida<br/>';
				} else {
					$mensaje_error.='ERROR: El evento notificado es inválido<br/>';
				}
			} else {
				$mensaje_error.='ERROR: No se ha especificado la lectura realizada<br/>';
			}
		}
	}
	else {
		//No se ha especificado el identificador del dispositivo
	}
	
	
?>

<html>
	<head>
		<title>Simulador de Lector RFID</title>
	</head>
	<body>
	<?
	// Se buscan los Lectores RFID
	$str_sql = 'SELECT id, codigo, descripcion, estado FROM dispositivo WHERE tipo = \'' . CONST_RFID . '\''; 
	$rs = $db->leer($str_sql);
	$str_select = '';
	$estado_rfid_actual = -1;
	while($r = $db->reg_array($rs))
	{
		if ($r['id']==$_GET['idRFID']) 
			$estado_rfid_actual = $r['estado'];
		$str_select .= '<option value="' . $r['id'] . '"' . (($r['id']==$_GET['idRFID'])?' selected="selected"':'') . '>' . $r['codigo'] . ' - ' . $r['descripcion'] . '</option>';
	}
	
	if ($str_select != '') {
		?>
		<script>
		function actualizar() {
			location.href='rfid.php?idRFID=' + document.formu.idRFID.value + '&lectura=' + document.formu.lectura.value;
		}
		</script>
		<form method="GET" action="rfid.php" name="formu">
			Seleccionar Lector RFID a simular: 
			<select name="idRFID" onchange="actualizar();">
				<option value="">SELECCIONAR</option>
				<?=$str_select?>
			</select>
			<br/><br/>
			<?
			// Se buscan las tarjetas
			$str_sql = 'SELECT codigo_tarjeta, nombre FROM guardia'; 
			$rs = $db->leer($str_sql);
			$str_opc_lecturas = '';
			while($r = $db->reg_array($rs))
			{
				$str_opc_lecturas .= '<option value="' . $r['codigo_tarjeta'] . '"' . (($r['codigo_tarjeta']==$_GET['lectura'])?' selected="selected"':'') . '>' . $r['nombre'] . '</option>';
			}
			?>
			Seleccionar la tarjeta sobre la que se opera: 
			<select name="lectura" onchange="actualizar();">
				<option value="">SELECCIONAR</option>
				<?=$str_opc_lecturas?>
			</select>
			<br/><br/>
			<?
			if ($estado_rfid_actual==CONST_EN_USO) {
				?>
				<input type="hidden" name="estado" value="<?=CONST_APAGADO?>" />
				<input type="submit" name="enviar_estado" value="Apagar" />
				<?
				// Se busca el último evento del Lector RFID:
				$str_sql = 'SELECT * FROM `log_rfid` WHERE `id_rfid`=\''.$_GET['idRFID'].'\' AND `codigo_tarjeta` = \''.$_GET['lectura'].'\' AND `timestamp_fin` IS NULL AND `timestamp_inicio`=(SELECT MAX(`timestamp_inicio`) AS ti FROM `log_rfid` WHERE `id_rfid`=\''.$_GET['idRFID'].'\' AND `codigo_tarjeta` = \''.$_GET['lectura'].'\') LIMIT 1;';
				$rs = $db->leer($str_sql);
				$evento_rfid_actual = 0; // NO DETECTANDO...
				while($r = $db->reg_array($rs))
				{
					$evento_rfid_actual = 1; // DETECTANDO...
				}
				
				if ($evento_rfid_actual==1) {
					?>
					<input type="hidden" name="evento" value="0" />
					<input type="submit" name="enviar_evento" value="Dejar de detectar" />
					<?
				}
				else {
					?>
					<input type="hidden" name="evento" value="1" />
					<input type="submit" name="enviar_evento" value="Comenzar a detectar" />
					<?
				}
			}
			else if ($estado_rfid_actual==CONST_APAGADO) {
				?>
				<input type="hidden" name="estado" value="<?=CONST_EN_USO?>" />
				<input type="submit" name="enviar_estado" value="Encender" />
				<?
			}
			else if ($estado_rfid_actual!=-1) {
				$mensaje_error.='Debe cambiar el estado del Lector RFID para poder utilizarlo en el simulador...<br/>';
			}
			?>
		</form>
		<?
	}
	else {
		$mensaje_error.='No hay Lectores RFID cargados en el sistema...<br/>';
	}
	?>
		<span style="color: green;"><?=$mensaje_resultado?></span>
		<span style="color: red;"><?=$mensaje_error?></span>
	</body>
</html>
