<h2 align="right"><?= $accion_usuarios ?> de Usuarios</h2>
<br>
<?=$errores ?>
<?=$mensaje ?>

<form method="POST" name="usuario_new">
	<input type="hidden" name="accion" value="usuario_new" />
	<fieldset>
		<legend><?= $keys['usuario.adm.datos'] ?></legend><br>
		<br>	
			<table width="70%" align="center" border="0">
				<tr>
					<td align="right"><p>* Usuario</p></td>
					<td><input type="text" name="usuario" value="<?= htmlentities($usuario) ?>" size="25" <?= $modificable ?> /></td>
					<td align="right"><p>* Clave</p></td>
					<td><input type="password" name="clave" size="25" maxlength="255"/></td>
				</tr>
				<tr>
					<td align="right"><p>Nombre</p></td>
					<td><input type="text" name="nombre" value="<?= htmlentities($nombre) ?>" size="25"/></td>
					<td align="right"><p>Apellido</p></td>
					<td><input type="text" name="apellido" value="<?= htmlentities($apellido) ?>" size="25" maxlength="25"/></td>
				</tr>
				<tr>
					<td align="right"><p>Email</p></td>
					<td><input type="text" name="email" value="<?= htmlentities($email) ?>" size="25" maxlength="50"/></td>
					<td align="right"><p>Otros mails</p></td>
					<td><input type="text" name="otros_mails" value="<?= htmlentities($otros_mails) ?>" size="25" maxlength="250"/></td>
				</tr>
				<tr>
					<td align="right"><p>Telefono 1</p></td>
					<td><input type="text" name="telefono1" value="<?= htmlentities($telefono1) ?>" size="25"/></td>
					<td align="right"><p>Telefono 2</p></td>
					<td><input type="text" name="telefono2" value="<?= htmlentities($telefono2) ?>" size="25" maxlength="25"/></td>
				</tr>
				<tr>
					<td align="right"><p>* Rol</p></td>
					<td><select name="id_rol" id="id_rol"><? ComboControl::Display($options_rol, $id_rol)?></select></td>
				</tr>
	  		</table>
	  		<br>
			<div align="center">
				<span class="botonGrad"><input type="submit"  name ="btnProcesar" value="Aceptar" class="boton" /></span>
				<span class="botonGrad"><input type="button"  value="Cancelar" class="boton" onClick="javascript: location.href='index.php?accion=usuario_adm'; return false;" /></span>
			</div>
		<br>
	</fieldset>
</form>
<br/>
