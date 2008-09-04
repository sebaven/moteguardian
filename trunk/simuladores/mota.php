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
	if (!empty($_GET['idMota']) && isset($_GET['idMota'])) {
		if (!empty($_GET['enviar_estado']) && isset($_GET['enviar_estado'])) {
			if ($_GET['estado'] == CONST_EN_USO) { // ENCENDER
				// Se actualiza el estado de la mota
				$str_sql = 'UPDATE `dispositivo` SET `estado`=\'' . CONST_EN_USO . '\' WHERE `id`=\''.$_GET['idMota'].'\' LIMIT 1;';
				$db->leer($str_sql);
				$mensaje_resultado='Operación de encendido de Mota "' . $_GET['idMota'] . '" recibida';
			} else if ($_GET['estado'] == CONST_APAGADO) { // APAGAR
				// Se actualiza el estado de la mota
				$str_sql = 'UPDATE `dispositivo` SET `estado`=\'' . CONST_APAGADO . '\' WHERE `id`=\''.$_GET['idMota'].'\' LIMIT 1;';
				$db->leer($str_sql);
				$mensaje_resultado='Operación de apagado de Mota "' . $_GET['idMota'] . '" recibida';
			} else {
				$mensaje_error='ERROR: La operación recibida es inválida';
			}
		}
		else if (!empty($_GET['enviar_evento']) && isset($_GET['enviar_evento'])) {
			if ($_GET['evento'] == '1') { // DETECCIÓN
				// Se inserta la nueva detección en la tabla log_mota
				$str_sql = 'INSERT INTO `log_mota` (`id_mota` , `timestamp_inicio`) VALUES (\''.$_GET['idMota'].'\', NOW( ));';
				$db->leer($str_sql);
				$mensaje_resultado='Notificación de detección de Mota "' . $_GET['idMota'] . '" recibida';
			} else if ($_GET['evento'] == '0') { // FIN DETECCIÓN
				// Se escribe el timestamp_fin de la última detección no finalizada de esta mota
				// IMPORTANTE: En la consulta interviene una tabla intermedia 'aux_log_mota' como workaround a un problema al seleccionar desde la tabla a actualizar. Extraído de: http://www.xaprb.com/blog/2006/06/23/how-to-select-from-an-update-target-in-mysql/
				$str_sql = 'UPDATE `log_mota` SET `timestamp_fin`=NOW( ) WHERE `id_mota`=\''.$_GET['idMota'].'\' AND `timestamp_inicio`=(SELECT ti FROM (SELECT MAX(`timestamp_inicio`) AS ti FROM `log_mota` WHERE `id_mota`=\''.$_GET['idMota'].'\' AND `timestamp_fin` IS NULL) as aux_log_mota) LIMIT 1;';
				$db->leer($str_sql);
				$mensaje_resultado='Notificación de fin de detección de Mota "' . $_GET['idMota'] . '" recibida';
			} else {
				$mensaje_error='ERROR: El evento notificado es inválido';
			}
		}
	}
	else {
		//No se ha especificado el identificador del dispositivo
	}
	
	
?>

<html>
	<head>
		<title>Simulador de Mota</title>
	</head>
	<body>
	<?
	// Se buscan las Motas
	$str_sql = 'SELECT id, codigo, descripcion, estado FROM dispositivo WHERE tipo = \'' . CONST_MOTA . '\''; 
	$rs = $db->leer($str_sql);
	$str_select = '';
	$estado_mota_actual = -1;
	while($r = $db->reg_array($rs))
	{
		if ($r['id']==$_GET['idMota']) 
			$estado_mota_actual = $r['estado'];
		$str_select .= '<option value="' . $r['id'] . '"' . (($r['id']==$_GET['idMota'])?' selected="selected"':'') . '>' . $r['codigo'] . ' - ' . $r['descripcion'] . '</option>';
	}
	
	if ($str_select != '') {
		?>
		<script>
		function actualizar() {
			location.href='mota.php?idMota=' + document.formu.idMota.value;
		}
		</script>
		<form method="GET" action="mota.php" name="formu">
			Seleccionar Mota a simular: 
			<select name="idMota" onchange="actualizar();">
				<option value="">SELECCIONAR</option>
				<?=$str_select?>
			</select>
			<br/><br/>
			<?
				if ($estado_mota_actual==CONST_EN_USO) {
					?>
					<input type="hidden" name="estado" value="<?=CONST_APAGADO?>" />
					<input type="submit" name="enviar_estado" value="Apagar" />
					<?
					// Se busca el último evento de la Mota:
					$str_sql = 'SELECT * FROM `log_mota` WHERE `id_mota`=\''.$_GET['idMota'].'\' AND `timestamp_inicio`=(SELECT MAX(`timestamp_inicio`) AS ti FROM `log_mota` WHERE `id_mota`=\''.$_GET['idMota'].'\') AND `timestamp_fin` IS NULL LIMIT 1;';
					$rs = $db->leer($str_sql);
					$evento_mota_actual = 0; // NO DETECTANDO...
					while($r = $db->reg_array($rs))
					{
						$evento_mota_actual = 1; // DETECTANDO...
					}
					
					if ($evento_mota_actual==1) {
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
				else if ($estado_mota_actual==CONST_APAGADO) {
					?>
					<input type="hidden" name="estado" value="<?=CONST_EN_USO?>" />
					<input type="submit" name="enviar_estado" value="Encender" />
					<?
				}
				else if ($estado_mota_actual!=-1) {
					$mensaje_error.='Estado desconocido de mota...';
				}
				?>
		</form>
		<?
		}
		else {
			$mensaje_error='No hay Motas cargadas en el sistema...';
		}
		?>
		<span style="color: green;"><?=$mensaje_resultado?></span>
		<span style="color: red;"><?=$mensaje_error?></span>
	</body>
</html>
