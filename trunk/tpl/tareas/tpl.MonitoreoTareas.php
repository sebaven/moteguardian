<br>
<h2 align="center"><?= $keys['monitoreo.tareas.titulo'] ?></h2><br/><br/>
<?= $errores ?>
<?= $mensaje ?>
<form method="post" name="monitoreo_tareas" action="">
	<input type="hidden" name="accion" value="monitoreo_tareas" />		
	<!-- B U S Q U E D A -->	
	<fieldset>
	<legend><?= $keys['monitoreo.tareas.fieldset.busqueda'] ?></legend>	
		<br/>
		<table border="0" style="width:100%" border="0">
			<tr>
				<td align="right" width="30%">
					<?= $keys['monitoreo.tareas.nombre.busqueda'] ?> 
				</td>
				<td align="center" width="70%">
					<input type="text" name="nombre_actividad" value="<?= $nombre_actividad ?>" size="40"/>
				</td>				
			</tr>				
			<tr height="15">
				<td colspan="4"></td>
			</tr>
			<tr>
				<td rowspan="2" align="right">
					<?= $keys['monitoreo.tareas.fecha.busqueda'] ?>
				</td>
				<td align="center" width="40%">
					<?= $keys['monitoreo.tareas.desde.busqueda'] ?>					
					<input type="text" id="fecha_desde" name="fecha_desde" readonly="readonly" size="10" value="<?= $fecha_desde ?>" />
					<button type="button" id="mostrarCalendario_desde">...</button><?= getCalendarDefinition( "fecha_desde", "mostrarCalendario_desde"); ?>
					<select id="horas_desde" name="horas_desde" ><? ComboControl::Display($options_horas_desde, $id_horas_desde)?></select> :
					<select id="minutos_desde" name="minutos_desde" ><? ComboControl::Display($options_minutos_desde, $id_minutos_desde)?></select>
				</td>					
			</tr>			
			<tr>
				<td align="center">
					<?= $keys['monitoreo.tareas.hasta.busqueda'] ?>		
					<input type="text" id="fecha_hasta" name="fecha_hasta" readonly="readonly" size="10" value="<?= $fecha_hasta ?>" />
					<button type="button" id="mostrarCalendario_hasta">...</button><?= getCalendarDefinition( "fecha_hasta", "mostrarCalendario_hasta"); ?>
					<select id="horas_hasta" name="horas_hasta" ><? ComboControl::Display($options_horas_hasta, $id_horas_hasta)?></select> :
					<select id="minutos_hasta" name="minutos_hasta" ><? ComboControl::Display($options_minutos_hasta, $id_minutos_hasta)?></select>
				</td>						
			</tr>
			<tr>
				<td align="right">
					<input type="checkbox" name="solo_erroneas" <?= $solo_erroneas_checked ?>/><?= $keys['monitoreo.tareas.check.solo.erroneas'] ?>
				</td>
				<td align="center">					
				</td>
			</tr>
		</table>
		<br/>
		<div align="center">
			<input type="submit" name="btnBuscar" value="Buscar" class="boton" />
			&nbsp;&nbsp;<a href="?accion=monitoreo_tareas"><input type="button" value="Limpiar" class="boton"/></a>						
		</div>
		<br/>		
	</fieldset>	
	<!-- F I N   B U S Q U E D A -->
</form>		
<!-- M O N I T O R E O -->
<?
for( $iActividad=0 ; $iActividad<(int)$cant_actividades ; $iActividad++ ) { 
	$nombre_var_actividad = 'actividad_'.$iActividad;	
	$nombre_var_cant_ejecuciones = 'cant_ejecuciones_'.$iActividad;			
?>	
	<!-- P A R A   C A D A   A C T I V I D A D -->	
	<fieldset>
	<legend><?= $$nombre_var_actividad ?></legend>
	<br/>
		<table width="100%">
		
		<?		
		for( $iEjecucion=0 ; $iEjecucion<(int)$$nombre_var_cant_ejecuciones ; $iEjecucion++ ) {
			$nombre_var_cant_archivos = 'cant_archivos_'.$iActividad.'_'.$iEjecucion;
			$nombre_var_fecha_inicio = 'fecha_inicio_'.$iActividad.'_'.$iEjecucion;
		?>				
			<!-- P A R A   C A D A   E J E C U C I O N -->
			<tr>
				<td colspan="3">
					Fecha de inicio: <?= $$nombre_var_fecha_inicio ?>
				</td>
			</tr>
			<tr height="2px" bgcolor="#000" >
				<td colspan="3"></td>
			</tr>						
			<tr height="20"><td></td></tr>
			<?			
			if($$nombre_var_cant_archivos==0){				
			?>
			<tr>
				<td></td>
				<td width="80%" align="center">No se inici&oacute; ninguna tarea para esta actividad</td>
				<td></td>
			</tr>
			<?	
			}
			for( $iArchivo=0 ; $iArchivo<(int)$$nombre_var_cant_archivos ; $iArchivo++ ){ 
				$nombre_var_estados_ejecutados = 'listado_estados_ejecutados_'.$iActividad.'_'.$iEjecucion.'_'.$iArchivo;
				$nombre_var_estados_restantes = 'listado_estados_restantes_'.$iActividad.'_'.$iEjecucion.'_'.$iArchivo;				
			?>
				<!-- P A R A   C A D A   A R C H I V O / T A R E A -->			
				<tr>
					<td></td>
					<td width="80%" align="center">
						<?= $$nombre_var_estados_ejecutados ?>						
						<?= $$nombre_var_estados_restantes ?>				
					</td>
					<td></td>
				</tr>			
				<!--  F I N   P A R A   C A D A   A R C H I V O / T A R E A -->
			<?
			}
			?>
			<tr height="20"><td></td></tr>			
			<!-- F I N   P A R A   C A D A   E J E C U C I O N -->
		<?
		}
		?>		
				
		</table>
	<br/><br/>
	</fieldset>	
	<!-- F I N   P A R A   C A D A   A C T I V I D A D -->
<?
}
?>
		
