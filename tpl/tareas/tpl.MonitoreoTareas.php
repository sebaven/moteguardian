<h2 align="right"><?= $keys['monitoreo.tareas.titulo'] ?></h2><br/>
<br>
<?= $errores ?>
<?= $mensaje ?>
<div align="center" class="app">
<form method="post" name="monitoreo_tareas" action="">
	<input type="hidden" name="accion" value="monitoreo_tareas" />		
	
	<!-- B U S Q U E D A -->	

	<fieldset>	
		<legend><?= $keys['monitoreo.tareas.fieldset.busqueda'] ?></legend>			
		<br>	
		<table style="width:100%">
			<tr>
				<td align="right" width="30%"><?= $keys['monitoreo.tareas.nombre.busqueda'] ?></td>
				<td align="center" width="70%">
					<input type="text" name="nombre_recoleccion" value="<?= htmlentities($nombre_recoleccion) ?>" size="40"/>
				</td>				
			</tr>				
			<tr height="15">
				<td colspan="4"></td>
			</tr>
			<tr>
				<td rowspan="2" align="right"><?= $keys['monitoreo.tareas.fecha.busqueda'] ?></td>
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
			<span class="botonGrad"><input type="submit" name="btnBuscar" value="Buscar" class="boton" /></span>		
			&nbsp;&nbsp;<span class="botonGrad"><a href="?accion=monitoreo_tareas"><input type="button" value="Limpiar" class="boton"/></a></span>									
		</div>
		
		<br/>
				
	</fieldset>
		
	<!-- F I N   B U S Q U E D A -->
		
</form>		


<!-- M O N I T O R E O -->
<br/>
<br/>
<div align="center">

<?
if($cant_recolecciones == 0){
	echo $keys['monitoreo.tareas.no.hay.recolecciones']."<br/>";	
}
for( $iRecoleccion=0 ; $iRecoleccion<(int)$cant_recolecciones ; $iRecoleccion++ ) { 
	$nombre_var_recoleccion = 'recoleccion_'.$iRecoleccion;		
	$nombre_var_cant_centrales = 'cant_centrales_'.$iRecoleccion;			
?>	

	<!-- P A R A   C A D A   R E C O L E C C I O N -->

		
	<fieldset>
	<legend><?= $$nombre_var_recoleccion ?></legend>
		<br/>		
		<table width="100%">	
		<?
		for( $iCentral=0 ; $iCentral<(int)$$nombre_var_cant_centrales ; $iCentral++ ){
			$nombre_var_central = "central_".$iRecoleccion."_".$iCentral;
			$nombre_var_cant_ejecuciones = "cant_ejecuciones_".$iRecoleccion."_".$iCentral;
		?>

		
			<!-- P A R A   C A D A   C E N T R A L   -->

	
			<tr> 
				<td> Central: <?= $$nombre_var_central ?> <img src="imagenes/b_search.png" style="cursor: pointer; vertical-align: middle;" onclick="mostrarEjecucionesDeCentral('<?= $iRecoleccion ?>','<?= $iCentral ?>');"> </td>
			</tr>
			<tr>
				<td>
					<div id="ejecuciones_central_<?= $iRecoleccion ?>_<?= $iCentral ?>" style="display:none;" >
					<table width="100%">					
									
			<?		
			for( $iEjecucion=0 ; $iEjecucion<(int)$$nombre_var_cant_ejecuciones ; $iEjecucion++ ) {			
				$nombre_var_cant_archivos = 'cant_archivos_'.$iRecoleccion.'_'.$iCentral."_".$iEjecucion;
				$nombre_var_fecha_inicio = 'fecha_inicio_'.$iRecoleccion.'_'.$iCentral."_".$iEjecucion;
				$nombre_var_log = "log_".$iRecoleccion.'_'.$iCentral."_".$iEjecucion;								
			?>			
			
			
				<!-- P A R A   C A D A   E J E C U C I O N -->
	
	
						<tr>
							<td></td>
							<td width="80%">
								Fecha de inicio: <?= $$nombre_var_fecha_inicio ?> <a onclick="popup('archivo','acciones/tareas/descargar.php?archivo=<?= $$nombre_var_log; ?>','400','400');" style="cursor:pointer; font-style: italic; font-weight:bold;" > (ver log) </a>
							</td>
							<td></td>
						</tr>						
						<tr height="2px"><td></td><td bgcolor="#000"></td><td></td></tr>						
				<?			
				if($$nombre_var_cant_archivos==0){				
				?>
						<tr>
							<td></td>
							<td width="80%" align="center">No se inici&oacute; ninguna tarea para esta recolecci&oacute;n</td>
							<td></td>
						</tr>			
				<?	
				}			
				for( $iArchivo=0 ; $iArchivo<(int)$$nombre_var_cant_archivos ; $iArchivo++ ){ 
					$nombre_var_estados_ejecutados = 'listado_estados_ejecutados_'.$iRecoleccion.'_'.$iCentral."_".$iEjecucion.'_'.$iArchivo;				
					$nombre_var_ubicacion_archivo = 'ubicacion_archivo_'.$iRecoleccion.'_'.$iCentral."_".$iEjecucion.'_'.$iArchivo;
					$nombre_var_imagen_estado =	'imagen_estado_'.$iRecoleccion.'_'.$iCentral."_".$iEjecucion.'_'.$iArchivo;
					$nombre_var_tarea = 'nombre_original_'.$iRecoleccion.'_'.$iCentral."_".$iEjecucion.'_'.$iArchivo;
				?>
				
				
				<!-- P A R A   C A D A   A R C H I V O / T A R E A -->
				
						<tr valign="middle">
							<td></td>
							<td width="80%" align="left" valign="middle">
								<?= $$nombre_var_tarea ?> 
								<img src="<?=$$nombre_var_imagen_estado?>" height="15px" style="vertical-align: middle;"/> 
								<? if($$nombre_var_ubicacion_archivo!='noValidado'){ ?><img style="cursor: pointer; vertical-align: middle;" src="imagenes/archivo.png" alt="ver fichero" title="ver fichero" onclick="popup('archivo','acciones/tareas/descargar.php?archivo=<?= $$nombre_var_ubicacion_archivo; ?>','400','400');" height="20px"/><? } ?> 
								<img src="imagenes/b_search.png" style="cursor: pointer; vertical-align: middle;" alt="Ver detalles" title="Ver detalles" onclick="mostrarOcultado('<?= 'ocultado_'.$iRecoleccion.'_'.$iCentral."_".$iEjecucion.'_'.$iArchivo ?>');" height="15px"/>
							</td>					
							<td></td>
						</tr>			
						<tr>
							<td></td>
							<td width="80%" align="center" valign="middle">
								<div id="<?= "ocultado_".$iRecoleccion."_".$iCentral."_".$iEjecucion."_".$iArchivo ?>" style="display:none;"><?= $$nombre_var_estados_ejecutados ?></div>										
							</td>
							<td></td>
						</tr>			


				<!--  F I N   P A R A   C A D A   A R C H I V O / T A R E A -->
				
				
				<?
				}
				?>
							
						
			<!-- F I N   P A R A   C A D A   E J E C U C I O N -->
			
			
		<?
		}
		?>
					</table>
					</div>
				</td>
			</tr>	
		
			
		<!-- F I N   P A R A   C E N T R A L  -->
	
		
		<?
		}
		?>	
		
		</table>	
	</fieldset>	
	
	
	<!-- F I N   P A R A   C A D A   R E C O L E C C I O N -->

<?
}
?>
</div>
<br>
<br>
</div>