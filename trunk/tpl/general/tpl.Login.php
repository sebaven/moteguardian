<div class="cuerpo">
<br>
<h2 align="center">Acceso al Sistema</h2>
<?= $errores ?>
<?= $mensaje ?>

<form method="POST" name="login" action="">
	<input type="hidden" name="accion" value="login" />
	<fieldset>
		<legend>Inicio de Sesi&oacute;n</legend><br>
		
		<table align="center" width="50%" border="0">
			<tr>
				<td align="center" colspan="4"><br/></td>				
			</tr>
			<tr align="left">
				<td>Usuario</td>
				<td><input type="text" name="nombre" value="<?= $nombre ?>" size="32" maxlength="64"/></td>
				<td>Clave</td>
				<td><input type="password" name="clave" value="" size="32" maxlength="255"/></td>
			</tr>
		</table>
		<br>
		<div align="center">
			<input type="submit" name="btnProcesar" value="Entrar" class="boton" />			
		</div>
		<br/>
	<br/>
	</fieldset>
</form>
<script type="text/javascript">document.forms[0].nombre.focus();</script>
</div>