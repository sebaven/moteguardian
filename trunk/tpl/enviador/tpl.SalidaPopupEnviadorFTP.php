<?=$errores ?>
<br>
<table height="100%" width="100%">
<tr valign="middle" align="center">
	<td><?=$mensaje ?></td>
</tr>
<tr valign="middle" align="center">
	<td>
		<form>
			<input type="button"  value="Finalizar" class="boton" onclick="javascript: window.opener.apretarBotonRecargar(); window.close(); return false;" />
		</form>
	</td>
</tr>
</table>