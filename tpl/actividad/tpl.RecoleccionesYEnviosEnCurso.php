<h2 align="right"><?= $keys['en.curso.titulo'] ?></h2><br/>
<br>
<?=$errores ?>
<?=$mensaje ?>
<div class="app" align="center">
<form method="GET" name="recolecciones_y_envios_en_curso" action="">
	<input type="hidden" name="accion" value="recolecciones_y_envios_en_curso" />
		
	<fieldset>
		<legend><?= $keys['en.curso.titulo.busqueda'] ?></legend><br/>
		<br/>
		<table width="50%">
			<tr>
				<td><?= $keys['en.curso.nombre'] ?></td>
				<td><input type="text" name="nombre" value="<?= htmlentities($nombre) ?>"/></td>
			</tr>
			<tr>
				<td><?= $keys['en.curso.nombre.fichero'] ?></td>
				<td><input type="text" name="nombre_de_fichero" value="<?= htmlentities($nombre_de_fichero) ?>"/></td>
			</tr>			
		</table>
		<br/>		
		<fieldset style="width: 70%; text-align: left;" >
			<legend><?= $keys['en.curso.fieldset.busqueda.interno'] ?></legend>
			<input type="checkbox" <?= $recolecciones_con_fallas_checked ?> name="recolecciones_con_fallas"/><?= $keys['en.curso.recolecciones.con.fallas'] ?><br/>
			<input type="checkbox" <?= $envios_con_fallas_checked ?> name="envios_con_fallas"/><?= $keys['en.curso.envios.con.fallas'] ?>			
		</fieldset>
		<br/>
		<fieldset style="width: 70%;">
			<legend><?= $keys['en.curso.fieldset.fecha.ejecucion'] ?></legend>
			<table width="70%">
				<tr>
					<td width="10%"><?= $keys['en.curso.fecha.desde'] ?></td>
					<td width="45%"><input type="text" id="fecha_desde" name="fecha_desde" readonly="readonly" size="10" value="<?= $fecha_desde?>" /><button type="button" id="mostrarCalendarioDesde">...</button><?= getCalendarDefinition( "fecha_desde", "mostrarCalendarioDesde"); ?></td>
					<td width="45%">
						<select name="id_horas_desde" ><? ComboControl::Display($options_horas_desde, $id_horas_desde)?></select> : <select name="id_minutos_desde" ><? ComboControl::Display($options_minutos_desde, $id_minutos_desde)?></select>
					</td>
				</tr>
				<tr>
					<td width="10%"><?= $keys['en.curso.fecha.hasta'] ?></td>
					<td width="45%"><input type="text" id="fecha_hasta" name="fecha_hasta" readonly="readonly" size="10" value="<?= $fecha_hasta?>" /><button type="button" id="mostrarCalendarioHasta">...</button><?= getCalendarDefinition( "fecha_hasta", "mostrarCalendarioHasta"); ?></td>
					<td width="45%">
						<select name="id_horas_hasta" ><? ComboControl::Display($options_horas_hasta, $id_horas_hasta)?></select> : <select name="id_minutos_hasta" ><? ComboControl::Display($options_minutos_hasta, $id_minutos_hasta)?></select>
					</td>
				</tr>				
			</table>
		</fieldset>				
		<br/>
		<br/>
		<table width="100%">
			<tr>	
				<td width="50%" align="right">
					<span class="botonGrad"><input type="submit" name="btnBuscar" value="<?= $keys['en.curso.btn.buscar'] ?>" class="boton"/></span>
				</td>
				<td>
					<span class="botonGrad"><input type="submit" name="btnLimpiar" value="<?= $keys['en.curso.btn.limpiar'] ?>" class="boton"/></span>
				</td>
			</tr>
		</table>
							
		<br/>
	</fieldset>	
	<br/>
	<br/>
	
	<?
		if($listado_recolecciones_con_fallas){
	?>
		<fieldset>
			<legend><?= $keys['en.curso.fieldset.recolecciones.con.fallas'] ?></legend>
			<br/>
			<?= $listado_recolecciones_con_fallas ?>
			<br/>
		</fieldset><br/><br/>
	<?
		}
	?>
	<br/>
	<br/>	
	<?
		if($listado_envios_con_fallas){
	?>
		<fieldset>
			<legend><?= $keys['en.curso.fieldset.envios.con.fallas'] ?></legend>
			<br/>
			<?= $listado_envios_con_fallas ?>			
			<br/>
		</fieldset><br/><br/>
	<?
		}
	?>
</form>
</div>
