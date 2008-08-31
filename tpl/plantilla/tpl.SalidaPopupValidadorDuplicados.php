<?=$errores ?>
<br>
<table height="100%" width="100%">
<tr valign="middle" align="center">
	<td><?=$mensaje ?></td>
</tr>
<tr valign="middle" align="center">
	<td>
		<form>
			<span class="botonGrad"><input type="button"  value="Finalizar" class="boton" onClick="javascript: window.opener.apretarBotonRecargar(); window.close(); return false;" /></span>
		</form>
	</td>
</tr>
</table>