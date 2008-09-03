<?
	//header('Content-type: text/xml');

	define("BASE_DIR_RELATIVO","../");
	define("BASE_DIR",realpath(BASE_DIR_RELATIVO)."/");

	include_once(BASE_DIR."comun/defines_app.php");
	include_once(BASE_DIR."ionix/config/ionix-config.php");
	include_once(BASE_DIR."ionix/data/Db.php"); 
	include_once(BASE_DIR."ionix/data/DbMySql.php"); 
	include_once(BASE_DIR."ionix/data/ConnectionManager.php");
	include_once(BASE_DIR."ionix/data/AbstractEntity.php");
	include_once(BASE_DIR."ionix/data/AbstractDAO.php");
	
/*
	// Obtener una conexión a la base
	$db = ConnectionManager::getConnection('');
	//$str_sql = 'SELECT id_sala FROM dispositivo WHERE codigo = \''.$_GET['idMota'].'\'';
	$str_sql = 'SELECT * FROM sala';
	$rs = $db->leer($str_sql);

		while($r = $db->reg_array($rs))
		{
			echo "<br/>" . $r['id'] . " - " . $r['descripcion'] . "<br/>";
			//foreach ($r as $key => $valor)
			//{
			//	echo "<br/>" . $key . " - " . $valor . "<br/>";
			//}
		}
*/

	// Obtener una conexión a la base
	$db = ConnectionManager::getConnection('');
	$str_sql = '(SELECT id_sala FROM dispositivo WHERE codigo = \''.$_GET['idMota'].'\')';
	$rs = $db->leer($str_sql);

		while($r = $db->reg_array($rs))
		{
			echo "<br/>" . $r['id'] . " - " . $r['descripcion'] . "<br/>";
			/*foreach ($r as $key => $valor)
			{
				echo "<br/>" . $key . " - " . $valor . "<br/>";
			}*/
		}

	echo "<?xml version='1.0' encoding='ISO-8859-1'?>\n<resultado>";

	if (isset($_GET['evento'])) {
		if (!empty($_GET['idMota']) && isset($_GET['idMota'])) {
			if ($_GET['evento'] == '1') { // DETECCIÓN
				// Se inserta la nueva detección en la tabla log_mota
				$str_sql = 'INSERT INTO `log_mota` (`id_mota` , `timestamp_inicio`) VALUES (\''.$_GET['idMota'].'\', NOW( ));';
				$db->leer($str_sql);
					?> 
					<estado>OK</estado>
					<descripcion>Notificación de detección de Mota "<?=$_GET['idMota']?>" recibida</descripcion>
					<?
			} else if ($_GET['evento'] == '0') { // FIN DETECCIÓN
				// Se escribe el timestamp_fin de la última detección no finalizada de esta mota
				// IMPORTANTE: En la consulta interviene una tabla intermedia 'aux_log_mota' como workaround a un problema al seleccionar desde la tabla a actualizar. Extraído de: http://www.xaprb.com/blog/2006/06/23/how-to-select-from-an-update-target-in-mysql/
				$str_sql = 'UPDATE `log_mota` SET `timestamp_fin`=NOW( ) WHERE `id_mota`=\''.$_GET['idMota'].'\' AND `timestamp_inicio`=(SELECT ti FROM (SELECT MAX(`timestamp_inicio`) AS ti FROM `log_mota` WHERE `id_mota`=\''.$_GET['idMota'].'\' AND `timestamp_fin` IS NULL) as aux_log_mota) LIMIT 1;';
				$db->leer($str_sql);
					?> 
					<estado>OK</estado>
					<descripcion>Notificación de fin de detección de Mota "<?=$_GET['idMota']?>" recibida</descripcion>
					<?
			} else {
				?> 
				<estado>ERROR</estado>
				<error>
					<codigo>0100</codigo>
					<descripcion>El evento notificado es inválido</descripcion>
				</error>
				<?
			}
		}
		else if (!empty($_GET['idRFID']) && isset($_GET['idRFID'])) {
			if (!empty($_GET['lectura']) && isset($_GET['lectura'])) {
				if ($_GET['evento'] == '1') { // DETECCIÓN
						// Se inserta la nueva detección en la tabla log_rfid
						$str_sql = 'INSERT INTO `log_rfid` (`id_rfid` , `timestamp_inicio`, `codigo_tarjeta`) VALUES (\''.$_GET['idRFID'].'\', NOW( ), \''.$_GET['lectura'].'\');';
						$db->leer($str_sql);
						?> 
						<estado>OK</estado>
						<descripcion>Lectura realizada por el lector RFID "<?=$_GET['idRFID']?>" recibida</descripcion>
						<?
				} else if ($_GET['evento'] == '0') { // FIN DETECCIÓN
					// Se escribe el timestamp_fin de la última detección de esta tarjeta no finalizada de este RFID
					// IMPORTANTE: En la consulta interviene una tabla intermedia 'aux_log_rfid' como workaround a un problema al seleccionar desde la tabla a actualizar. Extraído de: http://www.xaprb.com/blog/2006/06/23/how-to-select-from-an-update-target-in-mysql/
					$str_sql = 'UPDATE `log_rfid` SET `timestamp_fin`=NOW( ) WHERE `id_rfid`=\''.$_GET['idRFID'].'\' AND `timestamp_inicio`=(SELECT ti FROM (SELECT MAX(`timestamp_inicio`) AS ti FROM `log_rfid` WHERE `id_rfid`=\''.$_GET['idRFID'].'\' AND `timestamp_fin` IS NULL AND `codigo_tarjeta` = \''.$_GET['lectura'].'\') as aux_log_rfid) LIMIT 1;';
					$db->leer($str_sql);
						?> 
						<estado>OK</estado>
						<descripcion>Notificación de fin de detección del lector RFID "<?=$_GET['idRFID']?>" recibida</descripcion>
						<?
				} else {
					?> 
					<estado>ERROR</estado>
					<error>
						<codigo>0101</codigo>
						<descripcion>El evento notificado es inválido</descripcion>
					</error>
					<?
				}
			}
			else { // No se especificó la lectura...
				?> 
				<estado>ERROR</estado>
				<error>
					<codigo>1001</codigo>
					<descripcion>No se ha especificado la lectura efectuada por el lector RFID</descripcion>
				</error>
				<?
			}
		}
		else {
			?> 
			<estado>ERROR</estado>
			<error>
				<codigo>0010</codigo>
				<descripcion>No se ha especificado el identificador del dispositivo</descripcion>
			</error>
			<?
		}
	}
	else {
		?> 
		<estado>ERROR</estado>
		<error>
			<codigo>0001</codigo>
			<descripcion>No se ha especificado el evento a notificar por el dispositivo [1=INICIO/0=FIN]</descripcion>
		</error>
		<?
	}

?>
</resultado>