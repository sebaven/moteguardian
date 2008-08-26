<br>
<h2 align="center">Actividades</h2>
<?=$errores ?>
<?=$mensaje ?>

<form method="GET" name="actividad_bus" action="">
	<input type="hidden" name="accion" value="actividad_bus" />
	<fieldset>
		<legend><?= $keys['actividad.buscar'] ?></legend><br/>
		
		<table align="center" width="70%" border="0">
			<tr>
				<td align="center" colspan="4"><br/></td>				
			</tr>
			<tr align="left">
				<td><?= $keys['buscar.nombre.actividad']?></td>
				<td>
				    <input type="text" name="nombreActividad" value="<?= $nombreActividad ?>" size="32"/>
				</td>
				<td><?= $keys['buscar.nombre.central']?></td>
				<td>
					<input type="text" name="nombreCentral" value="<?= $nombreCentral ?>" size="32"/>
				</td>
			</tr>
		</table>
		<br>
		<div align="center">
			<input type="submit" name="btnBuscar" value="Buscar" class="boton" />
			&nbsp;&nbsp;<a href="?accion=actividad_bus"><input type="button" value="Limpiar" class="boton"/></a>			
		</div>
		<br/>
		<div align="center" style="color:gray;"><i>Si no completa ning&uacute;n campo trae todos las Actividades</i></div>
	<br/>
	</fieldset>
</form>
<script type="text/javascript">document.forms[0].nombreActividad.focus();</script>
<?= $listado ?>