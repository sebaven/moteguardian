<h2 align="right"><?= $keys['central.adm'] ?></h2>
<br>
<?=$errores ?>
<?=$mensaje ?>

<form method="GET" name="central_adm">
	<input type="hidden" name="accion" value="central_adm" />
	<fieldset>
		<legend><?= $keys['central.adm.datos'] ?></legend><br>
		<table width="95%" border="0">
			<tr align="left">
				<td align="right"><p><?= $keys['central.nombre'] ?></p></td>
				<td><input type="text" name="nombre" id="nombre" value="<?= htmlentities($nombre) ?>" size="20"/></td>
				<td align="right"><?= $keys['central.procesador'] ?></td>
				<td><input type="text" name="procesador" id="procesador" value="<?= htmlentities($procesador) ?>" size="25"/></td>
				<td align="right"><?= $keys['central.tecnologiaRecoleccion'] ?></td>
				<td><select id="id_tecnologia_recolector" name="id_tecnologia_recolector"><? ComboControl::Display($options_tecnologias, $id_tecnologia_recolector) ?></select></td>
			</tr>
		</table>
		<br><br>
		<div align="center">
		
			<span class="botonGrad"><input type="submit" name="btnBuscar" value="Buscar" class="boton" /></span>
			&nbsp;&nbsp;<span class="botonGrad"><a href="?accion=central_adm"><input type="button" value="Limpiar" class="boton"/></a></span>			
		</div>
		<br>
		<div align="center" style="color: gray;"><i><?= $keys['central.adm.info'] ?></i></div>
		<br>
	</fieldset>
</form>
<script type="text/javascript">document.forms[0].nombre.focus();</script>
<?= $listado ?>