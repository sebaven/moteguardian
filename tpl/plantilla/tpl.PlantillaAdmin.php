<h2 align="right">Administraci&oacute;n de plantillas</h2>
<br>
<?= $errores ?>
<?= $mensaje ?>
<form method="GET" name="plantilla_admin" action="">
	<input type="hidden" name="accion" value="plantilla_admin" />
		<br/><br/>
		<fieldset>
		<legend><b>Datos de la plantilla</b></legend>
			<br>
			<table border="0" style="width:100%" border="0">
				<tr>
					<td align="right">Plantilla: </td>
					<td align="left"><input type="text" name="nombre" value="<?= htmlentities($nombre) ?>" size="25"/></td>				
				</tr>
			</table>
			<br>
			<div align="center">
				<span class="botonGrad"><input type="submit" name="btnBuscar" value="Buscar" class="boton" /></span>
				&nbsp;&nbsp;<span class="botonGrad"><a href="?accion=plantilla_admin"><input type="button" value="Limpiar" class="boton"/></a></span>			
			</div>
			<br>
			<div align="center" style="color: gray;"><i><?= $keys['plantilla.adm.info'] ?></i></div>
			<br>
		</fieldset>
</form>
<br/>
<?= $listado ?>