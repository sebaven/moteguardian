<h2 align="right"><?=PropertiesHelper::GetKey('administrar.dispositivos')?></h2>
<br>
<?=$errores ?>
<?=$mensaje ?>

<form method="GET" name="dispositivo_adm" action="">
	<input type="hidden" name="accion" value="dispositivo_adm" />
	<fieldset>
		<legend><?= $keys['dispositivo.adm.datos'] ?></legend><br>
		<br>
        <h1>En principio traigo todo con el Buscar, después haré la búsqueda si hay tiempo</h1>
		<table border="0" style="width:100%" border="0">
			<tr>
				<td align="right"><p>Usuario</p></td>
				<td align="left"><input type="text" name="usuario" value="<?= htmlentities($usuario) ?>" size="25"/></td>
				<td align="right"><p>Rol</p></td>
				<td align="left"><select name="id_rol"><? ComboControl::Display($options_rol, $id_rol)?></select></td>
			</tr>
		</table>
		<br>
		<div align="center">
			<span class="botonGrad"><input type="submit" name="btnBuscar" value="Buscar" class="boton" /></span>
			&nbsp;&nbsp;<span class="botonGrad"><a href="?accion=usuario_adm"><input type="button" value="Limpiar" class="boton"/></a></span>			
		</div>
		<br>
	</fieldset>
</form>
<br/>
<br/>
<?= $listado ?>
<br/>