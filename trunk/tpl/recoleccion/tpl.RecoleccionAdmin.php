<h2 align="right"><?= $keys['recoleccion.admin.titulo'] ?></h2>
<br>
<?= $errores ?>
<?= $mensaje ?>
<div align="center">
<form method="get" name="recoleccion_admin">
<input type="hidden" name="accion" value="recoleccion_admin" />
	<fieldset>
		<legend><?= $keys['recoleccion.admin.titulo.fieldset.busqueda'] ?></legend>
		<br/>
		<table width="100%">
			<tr>
				<td width="25%" align="right"><?= $keys['recoleccion.admin.nombre.recoleccion'] ?></td>
				<td width="25%"><input type="text" name="nombre_recoleccion" value="<?= $nombre_recoleccion ?>"/></td>
				<td width="25%" align="right"><?= $keys['recoleccion.admin.nombre.central'] ?></td>
				<td width="25%"><input type="text" name="nombre_central" value="<?= $nombre_central ?>"/></td>								
			</tr>
			<tr>
				<td align="right"><?= $keys['recoleccion.admin.procesador'] ?></td>
				<td><input type="text" name="procesador" value="<?= $procesador ?>" /></td>
				<td align="right"><?= $keys['recoleccion.admin.tecnologia.central'] ?></td>
				<td>
					<select name="tecnologia_central"/>
						<?= ComboControl::Display($tecnologias_central, $id_tecnologia_central); ?>
					</select>
				</td>								
			</tr>			
			<tr>
				<td align="right"><?= $keys['recoleccion.admin.tecnologia.recolector'] ?></td>
				<td>
					<select name="tecnologia_recolector"/>
						<?= ComboControl::Display($tecnologias_recolector, $id_tecnologia_recolector); ?>
					</select>
				</td>
				<td align="right"><?= $keys['recoleccion.admin.solo.habilitadas'] ?></td>
				<td><input type="checkbox" name="solo_habilitadas" <?= $solo_habilitadas ?> <?= $solo_habilitadas_checked ?>/></td>								
			</tr>		
			<tr>				
				<td colspan="4"><br/><div align="center" style="color:gray;"><i>Si no completa ning&uacute;n campo trae todas las recolecciones</i></div></td>
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
</div>	