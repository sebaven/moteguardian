<h2 align="center">Configuraci&oacute;n de filtro <?= htmlentities($_GET['nombre_tipo_filtro']) ?></h2>
<?= $errores ?>
<?= $mensaje ?>
<form name="filtro_<?= htmlentities($_GET['nombre_tipo_filtro']) ?>" method="post">
<input type="hidden" name="accion" value="filtro_<?= htmlentities($_GET['nombre_tipo_filtro']) ?>"/>
<input type="hidden" name="id_nodo_plantilla" value="<?= $id_nodo_plantilla ?>"/>		
	
	<fieldset>
	<legend>Validar por</legend>		
		<table border="0" align="center">		
		<tr>
			<td> Configuraci&oacute;n </td>
			<td> <input type="text" value="<?= htmlentities($configuracion) ?>" name="configuracion" /> </td>
		</tr>
		<tr align="center">
			<td colspan="2">
			 	<span class="botonGrad"><input type="submit" name="btnProcesar"  value="Guardar configuracion"  class="boton" /></span>				
			</td>
		</tr>
		</table>
	</fieldset>
	
</form>