<h2 align="right">Cambiar contrase&ntilde;a</h2>
<br>
<?= $errores ?>
<?= $mensaje ?>
<form name="cambiar_pass" method="post">
	<input type="hidden" name="accion" value="cambiar_pass" />
	<br>
	<br>
	<table border="0" align="center">
		<tr><td align="right"><p>*Contrase&ntilde;a actual:</p></td>
			<td align="left"><input type="password" name="passActual">&nbsp;</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td align="right"><p>*Contrase&ntilde;a nueva:</p></td>
			<td align="left"><input type="password" name="passNuevo">&nbsp;</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td align="right"><p>*Confirmar contrase&ntilde;a:</p></td>
			<td align="left"><input type="password" name="passConfirm">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr><td><p><i>(*) Datos Obligatorios</i></p></td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr align="center">
			<td colspan="2">
			 	<span class="botonGrad"><input type="submit" name="btnProcesar"  value="Aceptar"  class="boton" /></span>
				<span class="botonGrad"><input type="button"  value="Cancelar" class="boton" onClick="javascript: location.href='index.php?accion=inicio'; return false;" /></span>
			</td>
		</tr>
	</table>
	<br>
	<br>
</form>
