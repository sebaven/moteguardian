<h2 align="right"><?= $keys['host.alta'] ?></h2>
<br>
<?=$errores ?>
<?=$mensaje ?>

<form method="POST" name="host_new">
	<input type="hidden" name="accion" value="host_new" />
	<input type="hidden" id="id_host" name="id_host" value="<?= $idHost ?>" />
	<input type="hidden" id="accion_tecnologia_envio" name="accion_tecnologia_envio" value="<?= $accion_tecnologia_envio ?>"/>
	<fieldset>
		<legend><?= $keys['host.alta.datos'] ?></legend><br>
		<table width="95%" border="0">
			<tr align="left">
				<td align="right">* <?= $keys['host.nombre'] ?></td>
				<td><input type="text" name="nombre" id="nombre" value="<?= htmlentities($nombre) ?>" size="15"/></td>
				<td align="right"><?= $keys['host.descripcion'] ?></td>
				<td><input type="text" name="descripcion" id="descripcion" value="<?= htmlentities($descripcion) ?>" size="15"/></td>
			</tr>
			<tr align="right">
				<td></td>
				<td></td>
				<td align="right">* <?= $keys['host.tecnologiaEnvio'] ?></td>
				<td align="left"><select id="id_tecnologia_envio" name="id_tecnologia_envio" onchange="javascript:setActionMethod('btnSetearAccion')"><? ComboControl::Display($options_tecnologias, $id_tecnologia_envio) ?></select></td>
			</tr>
		</table>
		<br/><br/>
		<div align="center">
			<input type="hidden" id="js_method_action" name="" value="Actualizar" />
			<span class="botonGrad"><input type="submit" value="<?= $keys['host.btnConfigurar'] ?>" name="btnConfigurar" class="boton"/></span>
			<span class="botonGrad"><input type="button" value="<?= $keys['host.btnCancelar'] ?>" class="boton" onClick="javascript: location.href='index.php?accion=host_adm'; return false;" /></span>
		</div>
		<br/>
	</fieldset>
</form>

<?
if ($valido) {
?>
<script type="text/javascript">
	configurarEnviador();
</script>
<?}?>