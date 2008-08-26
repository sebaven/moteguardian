<br>
<h2 align="center">Configuraci&oacute;n del sistema</h2>
<?= $errores ?>
<?= $mensaje ?>
<form name="configuracion_sistema" method="post">	
	<input type="hidden" name="accion" value="configuracion_sistema"/>
	<table border="0" align="center">
	<tr>
		<td>D&iacute;as de almacenamiento en disco</td>
		<td align="left"><input type="text" id="dias_disco" name="dias_disco" value="<?=$dias_disco?>" /></td>
	</tr>
	<tr>
		<td>D&iacute;as de almacenamiento en base de datos</td>
		<td align="left"><input type="text" id="dias_bd" name="dias_bd" value="<?=$dias_bd?>" /></td>
	</tr>	
	<tr align="center">
		<td colspan="2">
		 	<input type="submit" name="btnProcesar"  value="Guardar"  class="boton" />
			<input type="button"  value="Cancelar" class="boton" onClick="javascript: location.href='index.php?accion=inicio'; return false;" />
		</td>
	</tr>
	</table>
</form>