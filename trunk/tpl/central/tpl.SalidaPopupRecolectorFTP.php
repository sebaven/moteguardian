<br>
<?=$errores ?>
<table height="100%" width="100%">
<tr valign="middle" align="center">
	<td><?=$mensaje ?></td>
</tr>
<tr valign="middle" align="center">
	<td>
		<form>
			<input type="button"  value="Finalizar" class="boton" onclick="javascript: window.opener.location.href='index.php?accion=central_adm'; window.close(); return false;" />
		</form>
	</td>
</tr>
</table>