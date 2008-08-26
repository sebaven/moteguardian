<br>
<h2 align="center">Cambiar contrase&ntilde;a</h2>
<?= $errores ?>
<?= $mensaje ?>
<form name="cambiar_pass" method="post">
	<input type="hidden" name="accion" value="cambiar_pass" />
	<br>
	<br>
	<table border="0" align="center">
		<tr><td>Contrase&ntilde;a actual:</td>
			<td><input type="password" name="passActual">&nbsp;*</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>Contrase&ntilde;a nueva:</td>
			<td><input type="password" name="passNuevo">&nbsp;*</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>Confirmar contrase&ntilde;a:</td>
			<td><input type="password" name="passConfirm">&nbsp;*</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr><td><i>* Datos Obligatorios</i></td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr align="center">
			<td colspan="2">
			 	<input type="submit" name="btnProcesar"  value="Aceptar"  class="boton" />
				<input type="button"  value="Cancelar" class="boton" onClick="javascript: location.href='index.php?accion=inicio'; return false;" />
			</td>
		</tr>
	</table>
</form>
