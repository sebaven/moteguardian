<br>
<h2 style="text-align:center;"><?= $keys['enviadorFTP.conf'] ?></h2>

<?=$errores?>
<?=$mensaje?>
<form method="POST" name="enviador_FTP_conf">
	<input type="hidden" name="accion" value="enviador_FTP_conf" />
	<table>
		<tbody>
			<tr align="left">
				<td align="right"><?= $keys['enviadorFTP.nombreTecnologia'] ?></td>
				<td><input type="text" name="nombreTecnologia" id="nombreTecnologia" readonly="readonly" value="<?= $nombreTecnologia ?>"/></td>
				<td align="right"><?= $keys['enviadorFTP.renombrarFormato'] ?></td>
				<td><input type="text" name="formato_renombrado" id="formato_renombrado" value="<?= $formato_renombrado ?>"/></td>
			</tr>
		</tbody>
	</table>
	<fieldset>
		<legend><?= $keys['enviadorFTP.destinos'] ?></legend><br>
		<fieldset>
			<legend><b><?= $keys['enviadorFTP.configuracionDestino'] ?></b></legend><br/>
			<table>
			<tbody>
				<tr align="left">
					<td align="right"><?= $keys['enviadorFTP.direccionIP'] ?></td>
					<td><input type="text" name="direccionIP" id="direccionIP" value="<?= $direccionIP ?>"/>*</td>
					<td align="right"><?= $keys['enviadorFTP.ubicacion'] ?></td>
					<td><input type="text" name="ubicacion" id="ubicacion" value="<?= $ubicacion ?>"/>*</td>
				</tr>
				<tr align="left">
					<td align="right"><?= $keys['enviadorFTP.puerto'] ?></td>
					<td><input type="text" name="puerto" id="puerto" value="<?= $puerto ?>"/>*</td>
					<td align="right"><?= $keys['enviadorFTP.nroIntentos'] ?></td>
					<td><input type="text" name="nroIntentos" id="nroIntentos" value="<?= $nroIntentos ?>"/>*</td>
				</tr>
				<tr align="left">
					<td align="right"><?= $keys['enviadorFTP.usuario'] ?></td>
					<td><input type="text" name="usuarioFTP" id="usuarioFTP" value="<?= $usuarioFTP ?>"/>*</td>
					<td align="right"><?= $keys['enviadorFTP.password'] ?></td>
					<td><input type="password" name="passwordFTP" id="passwordFTP" value="<?= $passwordFTP ?>"/>*</td>
				</tr>
				<tr align="left">
					<td align="right"><?= $keys['enviadorFTP.modoPasivo'] ?></td>
					<td><input type="checkbox" name="modoPasivo" id="modoPasivo" <?= $modoPasivo ?>/></td>
					<td align="right"><?= $keys['enviadorFTP.timeout'] ?></td>
					<td><input type="text" name="timeout" id="timeout" value="<?= $timeout ?>"/> seg.</td>
				</tr>
			</tbody>
			</table>
			<br>
			<div align="center">
				<input type="hidden" id="js_method_action" name="js_method_action" value="" />
				<input type="button" name="btnAgregarDestinoFTP" value="<?= $keys['enviadorFTP.btnAgregarDestino'] ?>" class="boton" onclick="javascript:setActionMethod('btnAgregarDestinoFTP')" />
			</div>
		</fieldset>
		<br>
		<table class="origenesftp" align="center">
			<thead>
				<tr>
					<th><?= $keys['enviadorFTP.listadoDestinosFTP.nroOrden'] ?></th>
					<th><?= $keys['enviadorFTP.listadoDestinosFTP.direccionIP'] ?></th>
					<th><?= $keys['enviadorFTP.listadoDestinosFTP.ubicacion'] ?></th>
					<th><?= $keys['enviadorFTP.listadoDestinosFTP.puerto'] ?></th>
					<th><?= $keys['enviadorFTP.listadoDestinosFTP.intentos'] ?></th>
					<th><?= $keys['enviadorFTP.listadoDestinosFTP.usuario'] ?></th>
					<th><?= $keys['enviadorFTP.listadoDestinosFTP.modo_pasivo'] ?></th>
					<th><?= $keys['enviadorFTP.listadoDestinosFTP.timeout'] ?></th>
					<th><?= $keys['enviadorFTP.listadoDestinosFTP.editar'] ?></th>
					<th><?= $keys['enviadorFTP.listadoDestinosFTP.eliminar'] ?></th>
				</tr>
			</thead>
			<tbody>	     
				   <? if (isset($_SESSION['tabla_destinos'])) { 
				   		foreach($_SESSION['tabla_destinos'] as $nroFila => $fila ){
				   			if ($fila['bajaLogica'] != 1) {?>
					   			<tr>
					   			<?foreach($fila as $nombreCol => $valorCol){
		                        	if(($nombreCol != "passwordFTP") && ($nombreCol != "id") && ($nombreCol != "bajaLogica") && ($nombreCol != "idFila")) {?>
		                           	<td><?=$valorCol ?></td>
		                     	<?}
		                	}?>
		                	<td align="center"><input src="imagenes/b_search.png" type="image" name="btnEditarDestinoFTP" value="" onclick="javascript:setEditarDestino(<?= $fila['idFila'] ?>)" /></td>
		                	<td align="center"><input src="imagenes/b_drop.png" type="image" name="btnEliminarDestinoFTP" value="" onclick="javascript:setEliminarDestino(<?= $fila['idFila'] ?>)" /></td> 
	                	<?}?>
	                	</tr> 
	                <?}}?>
	        </tbody>
		</table>
		<input type="hidden" id="id_fila_borrar" name="id_fila_borrar" value=""/>
	</fieldset>
	<br/><br/>
	<div align="center">
	    <input type="hidden" name="limpiarSession" value="0"/>
		<input type="submit" name="btnProcesar" value="<?= $keys['enviadorFTP.btnAceptar'] ?>" class="boton"/>		
	</div>
	<br/>
</form>