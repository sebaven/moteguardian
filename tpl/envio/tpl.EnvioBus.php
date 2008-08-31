<h2 align="right"><?= $keys['envio.buscar.titulo'] ?></h2>
<br>
<?=$errores ?>
<?=$mensaje ?>
<div class="app" align="center">
<form method="GET" name="envio_bus" action="">
	<input type="hidden" name="accion" value="envio_bus" />
	
	<fieldset>
		<legend><?= $keys['envio.buscar.titulo.fieldset.busqueda'] ?></legend>
		<br/>
		
		<table width="100%">
			<tr>
				<td width="25%" align="right"><?= $keys['envio.buscar.nombre.envio'] ?></td>
				<td width="25%"><input type="text" name="nombre_envio"/></td>
				<td width="25%" align="right"><?= $keys['envio.buscar.nombre.recoleccion'] ?></td>
				<td width="25%"><input type="text" name="nombre_recoleccion"/></td>								
			</tr>
			<tr>
				<td align="right"><?= $keys['envio.buscar.nombre.host'] ?></td>
				<td><input type="text" name="nombre_host"/></td>
				<td align="right"><?= $keys['envio.buscar.nombre.central'] ?></td>
				<td><input type="text" name="nombre_central"/></td>						
			</tr>					
			<tr>				
				<td colspan="4"><br/><div align="center" style="color:gray;"><i>Si no completa ning&uacute;n campo trae todos los env&iacute;os</i></div></td>
			</tr>	
			<tr>
				<td colspan="2" align="right">							
					<br/>					
					<span class="botonGrad"><input type="submit" name="btnBuscar" id="btnBuscar" value="<?= $keys['recoleccion.btn.buscar'] ?>" class="boton"/></span>
				</td>
				<td colspan="2" align="left">
					<br/>
					<span class="botonGrad"><input type="submit" name="btnLimpiar" id="btnLimpiar" class="boton" value="<?= $keys['recoleccion.btn.limpiar'] ?>"/></span>
				</td>				
			</tr>
		</table>
	</fieldset>	
</form>
<br/>
<br/>

<?= $listado ?>
<br>
<br>
</div>