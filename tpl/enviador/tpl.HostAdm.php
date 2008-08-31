<h2 align="right"><?= $keys['host.adm'] ?></h2>
<br>
<?=$errores ?>
<?=$mensaje ?>

<form method="GET" name="host_adm">
	<input type="hidden" name="accion" value="host_adm" />
	<fieldset>
		<legend><?= $keys['host.adm.datos'] ?></legend><br>
		<table width="95%" border="0">
			<tr align="left">
				<td align="right"><?= $keys['host.nombre'] ?></td>
				<td><input type="text" name="nombre" id="nombre" value="<?= htmlentities($nombre) ?>" size="20"/></td>
				<td align="right"><?= $keys['host.tecnologiaEnvio'] ?></td>
				<td align="left"><select id="id_tecnologia_envio" name="id_tecnologia_envio" onchange="javascript:setActionMethod('btnSetearAccion')"><? ComboControl::Display($options_tecnologias, $id_tecnologia_envio) ?></select></td>
			</tr>
		</table>
		<br><br>
		<div align="center">
			<span class="botonGrad"><input type="submit" name="btnBuscar" value="Buscar" class="boton" /></span>
			&nbsp;&nbsp;<span class="botonGrad"><a href="?accion=host_adm"><input type="button" value="Limpiar" class="boton"/></a></span>			
		</div>
		<br>
		<div align="center" style="color: gray;"><i><?= $keys['host.adm.info'] ?></i></div>
		<br>
	</fieldset>
</form>
<script type="text/javascript">document.forms[0].nombre.focus();</script>
<?= $listado ?>