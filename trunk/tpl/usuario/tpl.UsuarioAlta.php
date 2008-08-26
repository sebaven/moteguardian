<br>
<h2 align="center"><?= $accion_usuarios ?> de Usuarios</h2>

<?=$errores ?>
<?=$mensaje ?>

<form method="POST" name="usuario_new">
	<input type="hidden" name="accion" value="usuario_new" />
	<fieldset>
		<legend><?= $keys['usuario.adm.datos'] ?></legend><br>
		<br>	
			<table width="50%" align="center" border="0">
				<tr>
					<td align="right">Usuario</td>
					<td><input type="text" name="usuario" value="<?= $usuario ?>" size="25" <?= $modificable ?> />*</td>
					<td align="right">Clave</td>
					<td><input type="password" name="clave" size="25" maxlength="255"/>*</td>
				</tr>
				<tr>
					<td align="right">Nombre</td>
					<td><input type="text" name="nombre" value="<?= $nombre ?>" size="25"/></td>
					<td align="right">Apellido</td>
					<td><input type="text" name="apellido" value="<?= $apellido ?>" size="25" maxlength="25"/></td>
				</tr>
				<tr>
					<td align="right">Email</td>
					<td><input type="text" name="email" value="<?= $email ?>" size="25" maxlength="50"/></td>
					<td align="right">Otros mails</td>
					<td><input type="text" name="otros_mails" value="<?= $otros_mails ?>" size="25" maxlength="250"/></td>
				</tr>
				<tr>
					<td align="right">Telefono 1</td>
					<td><input type="text" name="telefono1" value="<?= $telefono1 ?>" size="25"/></td>
					<td align="right">Telefono 2</td>
					<td><input type="text" name="telefono2" value="<?= $telefono2 ?>" size="25" maxlength="25"/></td>
				</tr>
				<tr>
					<td align="right">Rol</td>
					<td><select name="id_rol" id="id_rol"><? ComboControl::Display($options_rol, $id_rol)?></select>*</td>
				</tr>
	  		</table>
	  		<br>
			<div align="center">
				<input type="submit"  name ="btnProcesar" value="Aceptar" class="boton" />
				<input type="button"  value="Cancelar" class="boton" onClick="javascript: location.href='index.php?accion=usuario_adm'; return false;" />
			</div>
		<br>
	</fieldset>
</form>