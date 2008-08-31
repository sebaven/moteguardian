<h2 align="right">Configuraci&oacute;n del sistema</h2>
<br>
<?= $errores ?>
<?= $mensaje ?>
<form name="configuracion_sistema" method="post">	
	<input type="hidden" name="accion" value="configuracion_sistema"/>
	<br>
	<div align="center">
		<fieldset style="width: 75%;">
			<legend><?=$keys['input.mantenimiento.archivos']?></legend>
			<table align="center">
				<tr>
					<td align="right">* <?=$keys['input.dias_disco']?></td>
					<td align="left"><input type="text" id="dias_disco" name="dias_disco" value="<?=htmlentities($dias_disco)?>" /></td>
				</tr>
				<tr>
					<td align="right">* <?=$keys['input.dias_bd']?></td>
					<td align="left"><input type="text" id="dias_bd" name="dias_bd" value="<?=htmlentities($dias_bd)?>" /></td>
				</tr>
			</table>
		</fieldset>
	</div>
	<br>
	<div align="center">
		<fieldset style="width: 75%;">
			<legend><?=$keys['input.ubicaciones.ficheros']?></legend>
			<table align="center">
				<tr>
					<td align="right">* <?= $keys['input.ubicacion_ficheros_base'] ?></td>
					<td align="left"><input type="text" id="ubicacion_ficheros_base" name="ubicacion_ficheros_base" value="<?=htmlentities($ubicacion_ficheros_base)?>" /></td>
				</tr>
				<tr>
					<td align="right">* <?=$keys['input.ubicacion_ficheros_exito']?></td>
					<td align="left"><input type="text" id="ubicacion_ficheros_exito" name="ubicacion_ficheros_exito" value="<?=htmlentities($ubicacion_ficheros_exito)?>" /></td>
				</tr>	
				<tr>
					<td align="right">* <?=$keys['input.ubicacion_ficheros_error']?></td>
					<td align="left"><input type="text" id="ubicacion_ficheros_error" name="ubicacion_ficheros_error" value="<?=htmlentities($ubicacion_ficheros_error)?>" /></td>
				</tr>
				<tr>
					<td align="right">* <?= $keys['input.ubicacion_ficheros_enviados'] ?></td>
					<td align="left"><input type="text" id="ubicacion_ficheros_enviados" name="ubicacion_ficheros_enviados" value="<?=htmlentities($ubicacion_ficheros_enviados)?>" /></td>
				</tr>
			</table>
		</fieldset>
	</div>
	<br>
	<div align="center">
		<fieldset style="width: 75%;">
			<legend><?=$keys['input.configuracion.ftp.local']?></legend>
			<table align="center">
				<tr>
					<td align="right">* <?= $keys['input.configuracion.ftp.direccion.ip'] ?></td>
					<td align="left"><input type="text" id="direccion_ip" name="direccion_ip" value="<?=htmlentities($direccion_ip)?>" /></td>
					<td align="right">* <?= $keys['input.configuracion.ftp.puerto'] ?></td>
					<td align="left"><input type="text" id="puerto" name="puerto" value="<?=htmlentities($puerto)?>" /></td>
				</tr>
				<tr>
					<td align="right">* <?=$keys['input.configuracion.ftp.ubicacion.recoleccion']?></td>
					<td align="left"><input type="text" id="ubicacion_recoleccion" name="ubicacion_recoleccion" value="<?=htmlentities($ubicacion_recoleccion)?>" /></td>
					<td align="right">* <?=$keys['input.configuracion.ftp.ubicacion.envio']?></td>
					<td align="left"><input type="text" id="ubicacion_envio" name="ubicacion_envio" value="<?=htmlentities($ubicacion_envio)?>" /></td>
				</tr>	
				<tr>
					<td align="right">* <?=$keys['input.configuracion.ftp.intentos']?></td>
					<td align="left"><input type="text" id="intentos" name="intentos" value="<?=htmlentities($intentos)?>" /></td>
					<td align="right">* <?=$keys['input.configuracion.ftp.timeout']?></td>
					<td align="left"><input type="text" id="timeout" name="timeout" value="<?=htmlentities($timeout)?>" /></td>
				</tr>
				<tr>
					<td align="right">* <?=$keys['input.configuracion.ftp.usuario']?></td>
					<td align="left"><input type="text" id="usuario" name="usuario" value="<?=htmlentities($usuario)?>" /></td>
					<td align="right">* <?=$keys['input.configuracion.ftp.contrasenia']?></td>
					<td align="left"><input type="password" id="contrasenia" name="contrasenia" value="<?=htmlentities($contrasenia)?>" /></td>
				</tr>
			</table>
		</fieldset>
	</div>
	<table align="center">
	<tr>
		<td align="center" colspan="2">
			<br/>
			<span class="botonGrad"><input type="submit" value="<?= $boton_iniciado ?>" name="btnCambiarEstadoIniciado" <?= $boton_iniciado_disabled ?> class="boton"/><br/></span>			
			<br/>
		</td>
	</tr>
	<tr align="center">
		<td colspan="2">
		 	<span class="botonGrad"><input type="submit" name="btnProcesar"  value="Guardar"  class="boton" /></span>
			<span class="botonGrad"><input type="button"  value="Cancelar" class="boton" onClick="javascript: location.href='index.php?accion=inicio'; return false;" /></span>
		</td>
	</tr>
	</table>
</form>