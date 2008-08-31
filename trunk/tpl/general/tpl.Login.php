<div class="cuerpo">
<h2 align="center">Acceso al Sistema</h2>
<?= $errores ?>
<?= $mensaje ?>

<form method="POST" name="login" action="">
	<input type="hidden" name="accion" value="login" />
	<table width="950" border="0" cellpadding="10" cellspacing="0">
       <tr><td height="250" align="center" valign="middle"><table width="250" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
          <tr>
           <td>
			<fieldset>
				<legend>Inicio de Sesi&oacute;n</legend><br>
				<table>
                      <tr>
                        <td colspan="2">&nbsp;</td>
                      </tr>
                      <tr>
                        <td>Usuario</td>
                        <td><input type="text" name="nombre" value="<?= htmlentities($nombre) ?>" size="32" maxlength="64"/></td>
                      </tr>
                      <tr>
                        <td>Password</td>
                        <td><input type="password" name="clave" value="" size="32" maxlength="255"/></td>
                      </tr>
                </table>
				<br>
				<div align="center">
					<span class="botonGrad"><input type="submit" name="btnProcesar" value="Entrar" class="boton" /></span>			
				</div>
				<br/>
			<br/>
			</fieldset>
		</td>
	</tr>
	</table>
</form>
<script type="text/javascript">document.forms[0].nombre.focus();</script>
</div>