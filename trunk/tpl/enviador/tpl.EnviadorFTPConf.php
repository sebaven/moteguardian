<br>
<h2 style="text-align:center;"><?= $keys['enviadorFTP.conf'] ?></h2>

<?=$errores?>
<?=$mensaje?>
<form method="POST" name="enviador_FTP_conf">
	<input type="hidden" name="accion" value="enviador_FTP_conf" />
	<input type="hidden" name="nombreHost" value="<?= htmlentities($nombre_host) ?>" id="nombreHost" />
	<input type="hidden" name="descripcionHost" value="<?= htmlentities($descripcion_host) ?>" id="descripcionHost" />
	<input type="hidden" name="envio_local_seleccionado" value="<?= htmlentities($envio_local_seleccionado) ?>" id="envio_local_seleccionado"/>
	<table>
		<tbody>
			<tr align="left">
				<td align="right"><?= $keys['enviadorFTP.nombreTecnologia'] ?></td>
				<td><input type="text" name="nombreTecnologia" id="nombreTecnologia" readonly="readonly" value="<?= htmlentities($nombreTecnologia) ?>"/></td>
				<td align="right"><?= $keys['enviadorFTP.renombrarFormato'] ?></td>
				<td><input type="text" name="formato_renombrado" id="formato_renombrado" value="<?= htmlentities($formato_renombrado) ?>"/></td>
			</tr>
			<tr align="right">
				<td align="right"><?= $keys['enviadorFTP.envioLocal'] ?></td>
				<td><input type="checkbox" name="envio_local" id="envio_local" <?= $chkEnvioLocal ?> onclick="apretarBotonRecargar();"/></td>
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
					<td align="right">* <?= $keys['enviadorFTP.direccionIP'] ?></td>
					<td><input type="text" name="direccionIP" id="direccionIP" value="<?= htmlentities($direccionIP) ?>" <?= $direccionIPDisabled ?>/></td>
					<td align="right">* <?= $keys['enviadorFTP.ubicacion'] ?></td>
					<td><input type="text" name="ubicacion" id="ubicacion" value="<?= htmlentities($ubicacion) ?>" <?= $ubicacionDisabled ?>/></td>
				</tr>
				<tr align="left">
					<td align="right">* <?= $keys['enviadorFTP.puerto'] ?></td>
					<td><input type="text" name="puerto" id="puerto" value="<?= htmlentities($puerto) ?>" <?= $puertoDisabled ?>/></td>
					<td align="right">* <?= $keys['enviadorFTP.nroIntentos'] ?></td>
					<td><input type="text" name="nroIntentos" id="nroIntentos" value="<?= htmlentities($nroIntentos) ?>" <?= $nroIntentosDisabled ?>/></td>
				</tr>
				<tr align="left">
					<td align="right">* <?= $keys['enviadorFTP.usuario'] ?></td>
					<td><input type="text" name="usuarioFTP" id="usuarioFTP" value="<?= htmlentities($usuarioFTP) ?>" <?= $usuarioFTPDisabled ?>/></td>
					<td align="right">* <?= $keys['enviadorFTP.password'] ?></td>
					<td><input type="password" name="passwordFTP" id="passwordFTP" value="<?= htmlentities($passwordFTP) ?>" <?= $passwordFTPDisabled ?>/></td>
				</tr>
				<tr align="left">
					<td align="right"><?= $keys['enviadorFTP.modoPasivo'] ?></td>
					<td>
						<table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td>
									<input type="checkbox" name="modoPasivo" id="modoPasivo" <?= $modoPasivo ?> <?= $modoPasivoDisabled ?>/>
								</td>
								<td width="100px" align="right">
									<?= $keys['enviadorFTP.git'] ?>
								</td>
								<td>
									<input type="checkbox" name="GIT" id="GIT" <?= $modo_git ?> <?= $modoGitDisabled ?>/>
								</td>
							</tr>
						</table>
					</td>
					<td align="right"><?= $keys['enviadorFTP.timeout'] ?></td>
					<td><input type="text" name="timeout" id="timeout" value="<?= htmlentities($timeout) ?>" <?= $timeoutDisabled ?>/> seg.</td>
				</tr>
			</tbody>
			</table>
			<br>
			<div align="center">
				<input type="hidden" id="js_method_action" name="js_method_action" value="" />
				<span class="botonGrad"><input type="button" name="btnAgregarDestinoFTP" <?= $btnAgregarDeshabilitado ?> value="<?= $keys['enviadorFTP.btnAgregarDestino'] ?>" class="boton" onclick="javascript:setActionMethod('btnAgregarDestinoFTP')" /></span>
			</div>
		</fieldset>
		<br>
		<table class="origenesftp" cellspacing="0" cellpadding="0" border="0" align="center">
			<thead>
				<tr>
					<th width="34px"><?= $keys['enviadorFTP.listadoDestinosFTP.nroOrden'] ?></th>
					<th width="98px"><?= $keys['enviadorFTP.listadoDestinosFTP.direccionIP'] ?></th>
					<th><?= $keys['enviadorFTP.listadoDestinosFTP.ubicacion'] ?></th>
					<th width="40px"><?= $keys['enviadorFTP.listadoDestinosFTP.puerto'] ?></th>
					<th width="50px"><?= $keys['enviadorFTP.listadoDestinosFTP.intentos'] ?></th>
					<th width="80px"><?= $keys['enviadorFTP.listadoDestinosFTP.usuario'] ?></th>
					<th width="35px"><?= $keys['enviadorFTP.listadoDestinosFTP.modo_pasivo'] ?></th>
					<th width="44px"><?= $keys['enviadorFTP.listadoDestinosFTP.timeout'] ?></th>
					<th width="22px"><?= $keys['enviadorFTP.listadoDestinosFTP.git'] ?></th>
					<th width="34px"><?= $keys['enviadorFTP.listadoDestinosFTP.editar'] ?></th>
					<th width="45px"><?= $keys['enviadorFTP.listadoDestinosFTP.eliminar'] ?></th>
				</tr>
			</thead>
			<tbody>	     
				   <? if (isset($_SESSION['tabla_destinos'])) { 
				   		$nroFilaAlt = 1;
				   		foreach($_SESSION['tabla_destinos'] as $nroFila => $fila ){
				   			if ($fila['bajaLogica'] != 1) {?>
					   			<tr class="fila<?=$nroFilaAlt ?>">
					   			<?	if ($nroFilaAlt == 1) $nroFilaAlt = 2; else $nroFilaAlt = 1;
					   				foreach($fila as $nombreCol => $valorCol){
		                        	if(($nombreCol != "passwordFTP") && ($nombreCol != "id") && ($nombreCol != "bajaLogica") && ($nombreCol != "idFila")) {?>
		                           	<td><?= htmlentities($valorCol) ?></td>
		                     	<?}
		                	}?>
		                	<td align="center"><input src="imagenes/editar.gif" type="image" name="btnEditarDestinoFTP" value="" onclick="javascript:setEditarDestino(<?= $fila['idFila'] ?>)" /></td>
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
		<span class="botonGrad"><input type="submit" name="btnProcesar" value="<?= $keys['enviadorFTP.btnAceptar'] ?>" class="boton"/></span>
	</div>
	<div style="display: none;">
		<input type="submit" value="btnRecargar" name="btnRecargar" id="btnRecargar"/>
	</div>
	<br/>
</form>