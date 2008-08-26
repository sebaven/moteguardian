<br>
<h2 style="text-align:center;"><?= $keys['recolectorFTP.conf'] ?></h2>

<?=$errores?>
<?=$mensaje?>
<form method="POST" name="recolector_FTP_conf">
	<input type="hidden" name="accion" value="recolector_FTP_conf" id=""/>
	<input type="hidden" name="nombreCentral" value="<?= $nombre_central ?>" id="nombreCentral" />
	<input type="hidden" name="codigoCentral" value="<?= $codigo_central ?>" id="codigoCentral" />
	<input type="hidden" name="descripcionCentral" value="<?= $descripcion_central ?>" id="descripcionCentral" />
	<table>
	<tbody>
		<tr align="left">
			<td align="right"><?= $keys['recolectorFTP.nombreTecnologia'] ?></td>
			<td><input type="text" name="nombreTecnologia" id="nombreTecnologia" readonly="readonly" value="<?= $nombreTecnologia ?>"/></td>
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
					<td><input type="checkbox" name="firmaSHA" id="firmaSHA" <?=$chkFirmaSHA ?>/></td>
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
					<td align="right"><?= $keys['recolectorFTP.direccionIP'] ?></td>
					<td><input type="text" name="direccionIP" id="direccionIP" value="<?= $direccionIP ?>"/>*</td>
					<td align="right"><?= $keys['recolectorFTP.ubicacion'] ?></td>
					<td><input type="text" name="ubicacion" id="ubicacion" value="<?= $ubicacion ?>"/>*</td>
				</tr>
				<tr align="left">
					<td align="right"><?= $keys['recolectorFTP.puerto'] ?></td>
					<td><input type="text" name="puerto" id="puerto" value="<?= $puerto ?>"/>*</td>
					<td align="right"><?= $keys['recolectorFTP.nroIntentos'] ?></td>
					<td><input type="text" name="nroIntentos" id="nroIntentos" value="<?= $nroIntentos ?>"/>*</td>
				</tr>
				<tr align="left">
					<td align="right"><?= $keys['recolectorFTP.usuario'] ?></td>
					<td><input type="text" name="usuarioFTP" id="usuarioFTP" value="<?= $usuarioFTP ?>"/>*</td>
					<td align="right"><?= $keys['recolectorFTP.password'] ?></td>
					<td><input type="password" name="passwordFTP" id="passwordFTP" value="<?= $passwordFTP ?>"/>*</td>
				</tr>
				<tr align="left">
					<td align="right"><?= $keys['recolectorFTP.modoPasivo'] ?></td>
					<td><input type="checkbox" name="modoPasivo" id="modoPasivo" <?= $modoPasivo ?>/></td>
					<td align="right"><?= $keys['recolectorFTP.timeout'] ?></td>
					<td><input type="text" name="timeout" id="timeout" value="<?= $timeout ?>"/> seg.</td>
				</tr>
			</tbody>
			</table>
			<br>
			<div align="center">
				<input type="hidden" id="js_method_action" name="js_method_action" value="" />
				<input type="button" name="btnAgregarOrigenFTP" value="<?= $keys['recolectorFTP.btnAgregarOrigen'] ?>" class="boton" onclick="javascript:setActionMethod('btnAgregarOrigenFTP');" />
			</div>
		</fieldset>
		<br>
		<table class="origenesftp" align="center">
			<thead>
				<tr>
					<th><?= $keys['recolectorFTP.listadoOrigenesFTP.nroOrden'] ?></th>
					<th><?= $keys['recolectorFTP.listadoOrigenesFTP.direccionIP'] ?></th>
					<th><?= $keys['recolectorFTP.listadoOrigenesFTP.ubicacion'] ?></th>
					<th><?= $keys['recolectorFTP.listadoOrigenesFTP.puerto'] ?></th>
					<th><?= $keys['recolectorFTP.listadoOrigenesFTP.intentos'] ?></th>
					<th><?= $keys['recolectorFTP.listadoOrigenesFTP.usuario'] ?></th>
					<th><?= $keys['recolectorFTP.listadoOrigenesFTP.modo_pasivo'] ?></th>
					<th><?= $keys['recolectorFTP.listadoOrigenesFTP.timeout'] ?></th>
					<th><?= $keys['recolectorFTP.listadoOrigenesFTP.editar'] ?></th>
					<th><?= $keys['recolectorFTP.listadoOrigenesFTP.eliminar'] ?></th>
				</tr>
			</thead>
			<tbody>	     
				   <? if (isset($_SESSION['tabla_origenes'])) { 
				   		foreach($_SESSION['tabla_origenes'] as $nroFila => $fila ){
				   			if ($fila['bajaLogica'] != 1) {?>
					   			<tr>
					   			<?foreach($fila as $nombreCol => $valorCol){
		                        	if(($nombreCol != "passwordFTP") && ($nombreCol != "id") && ($nombreCol != "bajaLogica") && ($nombreCol != "idFila")) {?>
		                           	<td><?=$valorCol ?></td>
		                     	<?}
		                	}?>
		                	<td align="center"><input src="imagenes/b_search.png" type="image" name="btnEditarOrigenFTP" value="" onclick="javascript:setEditarOrigen(<?= $fila['idFila'] ?>)" /></td>
		                	<td align="center"><input src="imagenes/b_drop.png" type="image" name="btnEliminarOrigenFTP" value="" onclick="javascript:setEliminarOrigen(<?= $fila['idFila'] ?>)" /></td> 
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
		<input type="submit" name="btnProcesar" value="<?= $keys['recolectorFTP.btnAceptar'] ?>" class="boton"/>		
	</div>
	<br>
</form>
<script type="text/javascript">
<!--AcÃ se unescapean los datos que fueron escapeados-->
document.getElementById("nombreCentral").value = unescape(document.getElementById("nombreCentral").value);
document.getElementById("codigoCentral").value = unescape(document.getElementById("codigoCentral").value);
document.getElementById("descripcionCentral").value = unescape(document.getElementById("descripcionCentral").value);
</script>