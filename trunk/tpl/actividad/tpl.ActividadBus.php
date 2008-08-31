<h2 align="right">Actividades</h2>
<br>
<?=$errores ?>
<?=$mensaje ?>

<form method="GET" name="actividad_bus" action="">
	<input type="hidden" name="accion" value="actividad_bus" />
	<fieldset>
		<legend><?= $keys['actividad.buscar'] ?></legend><br/>
		
		<table align="center" width="70%" border="0">			
			<tr align="left">
				<td><?= $keys['buscar.nombre.actividad']?></td>
				<td>
				    <input type="text" name="nombreActividad" value="<?= htmlentities($nombreActividad) ?>" size="32"/>
				</td>				
			</tr>
		</table>
		<br>
		<div align="center">
			<span class="botonGrad"><input type="submit" name="btnBuscar" value="Buscar" class="boton" /></span>
			&nbsp;&nbsp;<span class="botonGrad"><a href="?accion=actividad_bus"><input type="button" value="Limpiar" class="boton"/></a></span>			
		</div>
		<br/>
		<div align="center" style="color:gray;"><i>Si no completa ning&uacute;n campo trae todas las Actividades</i></div>
	<br/>
	</fieldset>
</form>
<script type="text/javascript">document.forms[0].nombreActividad.focus();</script>
<?= $listado ?>