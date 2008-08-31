<br>
<h2 style="text-align:center;"><?= $keys['recolectorFTP.conf'] ?></h2>

<?=$errores?>
<?=$mensaje?>
<form method="POST" name="recolector_FTP_conf">
	<input type="hidden" name="accion" value="recolector_FTP_conf" id=""/>
	<input type="hidden" name="nombreCentral" value="<?= htmlentities($nombre_central) ?>" id="nombreCentral" />
	<input type="hidden" name="procesadorCentral" value="<?= htmlentities($procesador_central) ?>" id="procesadorCentral" />
	<input type="hidden" name="descripcionCentral" value="<?= htmlentities($descripcion_central) ?>" id="descripcionCentral" />
	<input type="hidden" name="recoleccion_local_seleccionada" value="<?= htmlentities($recoleccion_local_seleccionada) ?>" id="recoleccion_local_seleccionada"/>
	<table>
	<tbody>
		<tr align="left">
			<td align="right"><?= $keys['recolectorFTP.nombreTecnologia'] ?></td>
			<td><input type="text" name="nombreTecnologia" id="nombreTecnologia" readonly="readonly" value="<?= htmlentities($nombreTecnologia) ?>"/></td>
			<td align="right"><?= $keys['recolectorFTP.recoleccionLocal'] ?></td>
			<td><input type="checkbox" name="recoleccion_local" id="recoleccion_local" <?= $chkRecoleccionLocal ?> onclick="apretarBotonRecargar();"/></td>
		</tr>
		<tr>
			<td>* <?= $keys['recoleccion.conf.filtro.seleccion.ficheros']?></td>
			<td><input type="text" name="filtro_seleccion_ficheros" value="<?= htmlentities($filtro_seleccion_ficheros) ?>"/></td>
		</tr>
		<tr align="left">
			<td align="right"><?= $keys['recolectorFTP.luegoTransferencia'] ?></td>
			<td><select id="id_luegoTransferencia" name="id_luegoTransferencia"><? ComboControl::Display($options_luegoTransferencia, $id_luegoTransferencia) ?></select></td>
			<td align="right"><?= $keys['recolectorFTP.buscarRecursivamente'] ?></td>
			<td><input type="checkbox" name="busqueda_recursiva" id="busqueda_recursiva" <?=$chkBusquedaRecursiva ?>/></td>
		</tr>
	</tbody>
	</table>
	<fieldset>
		<legend><?= $keys['recolectorFTP.validaciones'] ?></legend><br>
		<table>
			<tbody>
				<tr>
					<td align="right"><?= $keys['recolectorFTP.firmaSHA'] ?></td>
					<td><input type="checkbox" name="firmaSHA" id="firmaSHA" value="<?=$chkFirmaSHA ?>"/></td>
					<td align="right"><?= $keys['recolectorFTP.tamanio'] ?></td>
					<td><input type="checkbox" name="tamanio" id="tamanio" <?=$chkTamanio ?>/></td>
				</tr>
			</tbody>
		</table>
	</fieldset>
	<fieldset>
		<legend><?= $keys['recolectorFTP.origenes'] ?></legend><br>
		<fieldset>
			<legend><?= $keys['recolectorFTP.configuracionOrigen'] ?></legend><br>
			<table>
			<tbody>
				<tr align="left">
					<td align="right">* <?= $keys['recolectorFTP.direccionIP'] ?></td>
					<td><input type="text" name="direccionIP" id="direccionIP" value="<?= htmlentities($direccionIP) ?>" <?= $direccionIPDisabled ?>/></td>
					<td align="right">* <?= $keys['recolectorFTP.ubicacion'] ?></td>
					<td><input type="text" name="ubicacion" id="ubicacion" value="<?= htmlentities($ubicacion) ?>" <?= $ubicacionDisabled ?>/></td>
				</tr>
				<tr align="left">
					<td align="right">* <?= $keys['recolectorFTP.puerto'] ?></td>
					<td><input type="text" name="puerto" id="puerto" value="<?= htmlentities($puerto) ?>" <?= $puertoDisabled ?>/></td>
					<td align="right">* <?= $keys['recolectorFTP.nroIntentos'] ?></td>
					<td><input type="text" name="nroIntentos" id="nroIntentos" value="<?= htmlentities($nroIntentos) ?>" <?= $nroIntentosDisabled ?>/></td>
				</tr>
				<tr align="left">
					<td align="right">* <?= $keys['recolectorFTP.usuario'] ?></td>
					<td><input type="text" name="usuarioFTP" id="usuarioFTP" value="<?= htmlentities($usuarioFTP) ?>" <?= $usuarioFTPDisabled ?>/></td>
					<td align="right">* <?= $keys['recolectorFTP.password'] ?></td>
					<td><input type="password" name="passwordFTP" id="passwordFTP" value="<?= htmlentities($passwordFTP) ?>" <?= $passwordFTPDisabled ?>/></td>
				</tr>
				<tr align="left">
					<td align="right"><?= $keys['recolectorFTP.modoPasivo'] ?></td>
					<td><input type="checkbox" name="modoPasivo" id="modoPasivo" <?= $modoPasivo ?> <?= $modoPasivoDisabled ?>/></td>
					<td align="right"><?= $keys['recolectorFTP.timeout'] ?></td>
					<td><input type="text" name="timeout" id="timeout" value="<?= htmlentities($timeout) ?>" <?= $timeoutDisabled ?>/> seg.</td>
				</tr>
			</tbody>
			</table>
			<br>
			<div align="center">
				<input type="hidden" id="js_method_action" name="js_method_action" value="" />
				<span class="botonGrad"><input type="button" name="btnAgregarOrigenFTP" <?= $btnAgregarDeshabilitado ?> value="<?= $keys['recolectorFTP.btnAgregarOrigen'] ?>" class="boton" onclick="javascript:setActionMethod('btnAgregarOrigenFTP');" /></span>
			</div>
		</fieldset>
		<br>
		<table class="origenesftp" cellspacing="0" cellpadding="0" border="0" align="center">
			<thead>
				<tr>
					<th width="34px"><?= $keys['recolectorFTP.listadoOrigenesFTP.nroOrden'] ?></th>
					<th width="98px"><?= $keys['recolectorFTP.listadoOrigenesFTP.direccionIP'] ?></th>
					<th><?= $keys['recolectorFTP.listadoOrigenesFTP.ubicacion'] ?></th>
					<th width="40px"><?= $keys['recolectorFTP.listadoOrigenesFTP.puerto'] ?></th>
					<th width="50px"><?= $keys['recolectorFTP.listadoOrigenesFTP.intentos'] ?></th>
					<th width="80px"><?= $keys['recolectorFTP.listadoOrigenesFTP.usuario'] ?></th>
					<th width="35px"><?= $keys['recolectorFTP.listadoOrigenesFTP.modo_pasivo'] ?></th>
					<th width="44px"><?= $keys['recolectorFTP.listadoOrigenesFTP.timeout'] ?></th>
					<th width="34px"><?= $keys['recolectorFTP.listadoOrigenesFTP.editar'] ?></th>
					<th width="45px"><?= $keys['recolectorFTP.listadoOrigenesFTP.eliminar'] ?></th>
				</tr>
			</thead>
			<tbody>	     
				   <? if (isset($_SESSION['tabla_origenes'])) { 
				   		$nroFilaAlt = 1;
				   		foreach($_SESSION['tabla_origenes'] as $nroFila => $fila ){
				   			if ($fila['bajaLogica'] != 1) {?>
					   			<tr class="fila<?=$nroFilaAlt ?>">
					   			<?	if ($nroFilaAlt == 1) $nroFilaAlt = 2; else $nroFilaAlt = 1;
					   				foreach($fila as $nombreCol => $valorCol){
		                        	if(($nombreCol != "passwordFTP") && ($nombreCol != "id") && ($nombreCol != "bajaLogica") && ($nombreCol != "idFila")) {?>
		                           	<td><?= htmlentities($valorCol) ?></td>
		                     	<?}
		                	}?>
		                	<td align="center"><input src="imagenes/editar.png" type="image" name="btnEditarOrigenFTP" value="" onclick="javascript:setEditarOrigen(<?= $fila['idFila'] ?>)" /></td>
		                	<td align="center"><input src="imagenes/eliminar.png" type="image" name="btnEliminarOrigenFTP" value="" onclick="javascript:setEliminarOrigen(<?= $fila['idFila'] ?>)" /></td> 
	                	<?}?>
	                	</tr> 
	                <?}}?>
	        </tbody>
		</table>
		<input type="hidden" id="id_fila_borrar" name="id_fila_borrar" value=""/>
	</fieldset>
	<br><br>
	<div align="center">
	    <input type="hidden" name="limpiarSession" value="0"/>
		<span class="botonGrad"><input type="submit" name="btnProcesar" value="<?= $keys['recolectorFTP.btnAceptar'] ?>" class="boton"/></span>
	</div>
	<div style="display: none;">
		<input type="submit" value="btnRecargar" name="btnRecargar" id="btnRecargar"/>
	</div>
	<br>
</form>
<script type="text/javascript">
<!--AcÃ se unescapean los datos que fueron escapeados-->
document.getElementById("nombreCentral").value = unescape(document.getElementById("nombreCentral").value);
document.getElementById("procesadorCentral").value = unescape(document.getElementById("procesadorCentral").value);
document.getElementById("descripcionCentral").value = unescape(document.getElementById("descripcionCentral").value);
</script>