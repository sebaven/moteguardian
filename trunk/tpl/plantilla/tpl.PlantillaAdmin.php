<br>
<h2 align="center">Administraci&oacute;n de plantillas</h2>
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
					<td align="right">Nombre: </td>
					<td align="left"><input type="text" name="nombre" value="<?= $nombre ?>" size="25"/></td>				
				</tr>
			</table>
			<br>
			<div align="center">
				<input type="submit" name="btnBuscar" value="Buscar" class="boton" />
				&nbsp;&nbsp;<a href="?accion=plantilla_admin"><input type="button" value="Limpiar" class="boton"/></a>			
			</div>
			<br>
			<div align="center" style="color: gray;"><i><?= $keys['plantilla.adm.info'] ?></i></div>
			<br>
		</fieldset>
</form>
<br/>
<?= $listado ?>