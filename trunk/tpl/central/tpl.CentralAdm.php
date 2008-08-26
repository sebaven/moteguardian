<br>
<h2 align="center"><?= $keys['central.adm'] ?></h2>

<?=$errores ?>
<?=$mensaje ?>

<form method="GET" name="central_adm">
	<input type="hidden" name="accion" value="central_adm" />
	<fieldset>
		<legend><?= $keys['central.adm.datos'] ?></legend><br>
		<table width="95%" border="0">
			<tr align="left">
				<td align="right"><?= $keys['central.nombre'] ?></td>
				<td><input type="text" name="nombre" id="nombre" value="<?= $nombre ?>" size="20"/></td>
				<td align="right"><?= $keys['central.codigo'] ?></td>
				<td><input type="text" name="codigo" id="codigo" value="<?= $codigo ?>" size="25"/></td>
				<td align="right"><?= $keys['central.tecnologiaRecoleccion'] ?></td>
				<td><select id="id_tecnologia_recolector" name="id_tecnologia_recolector"><? ComboControl::Display($options_tecnologias, $id_tecnologia_recolector) ?></select></td>
			</tr>
		</table>
		<br><br>
		<div align="center">
			<input type="submit" name="btnBuscar" value="Buscar" class="boton" />
			&nbsp;&nbsp;<a href="?accion=central_adm"><input type="button" value="Limpiar" class="boton"/></a>			
		</div>
		<br>
		<div align="center" style="color: gray;"><i><?= $keys['central.adm.info'] ?></i></div>
		<br>
	</fieldset>
</form>
<script type="text/javascript">document.forms[0].nombre.focus();</script>
<?= $listado ?>