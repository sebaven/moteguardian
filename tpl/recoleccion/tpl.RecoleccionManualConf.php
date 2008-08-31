<h2 align="right"><?= $keys['recoleccionManual.conf.titulo'] ?></h2>
<br/>
<?= $errores ?>
<?= $mensaje ?>
<div align="center" class="app">
<form method="get" name="recoleccion_manual_conf">
	<input type="hidden" name="accion" value="recoleccion_manual_conf" />		
	<input type="hidden" id="id_recoleccion_manual" name="id_recoleccion_manual" value=<?= $id_recoleccion_manual ?> />	
	
	<!-- BUSQUEDA DE CENTRALES -->
	
	<div style="width: 70%;">
		<fieldset>
			<legend><?= $keys['recoleccionManual.conf.busqueda.centrales'] ?></legend>
			<br/>
			<table width="100%">
				<tr>
					<td align="right"><?= $keys['recoleccionManual.conf.busqueda.recoleccion.nombre'] ?></td>
					<td><input type="text" name="nombre_recoleccion" value="<?= htmlentities($nombre_recoleccion) ?>"/></td>
				</tr>
				<tr>
					<td align="right"><?= $keys['recoleccionManual.conf.busqueda.recoleccion.fechaVigenciaDesde'] ?></td>
					<td>
						<input type="text" id="fecha_desde" name="fecha_desde" readonly="readonly" size="10" value="<?= $fecha_desde ?>" />
						<button type="button" id="mostrarCalendario_desde"><img src="imagenes/calendario.png"/></button><?= getCalendarDefinition( "fecha_desde", "mostrarCalendario_desde"); ?>
					</td>
					<td align="right"><?= $keys['recoleccionManual.conf.busqueda.recoleccion.fechaVigenciaHasta'] ?></td>
					<td>
						<input type="text" id="fecha_hasta" name="fecha_hasta" readonly="readonly" size="10" value="<?= $fecha_hasta ?>" />
						<button type="button" id="mostrarCalendario_hasta"><img src="imagenes/calendario.png"/></button><?= getCalendarDefinition( "fecha_hasta", "mostrarCalendario_hasta"); ?>
					</td>
				</tr>
				<tr>
					<td width="25%" align="right"><?= $keys['recoleccionManual.conf.busqueda.centrales.select.tecnologia.central'] ?></td>
					<td width="25%">
						<select name="id_tecnologia_central" id="id_tecnologia_central">
							<?= ComboControl::Display($tecnologias_central, $id_tecnologia_central); ?>
						</select>
					</td>					
					<td width="25%" align="right"><?= $keys['recoleccionManual.conf.busqueda.centrales.select.tecnologia.recoleccion'] ?></td>
					<td width="25%">
						<select name="id_tecnologia_recoleccion" id="id_tecnologia_recoleccion">
						<?= ComboControl::Display($tecnologias_recoleccion, $id_tecnologia_recoleccion); ?>
						</select>
					</td>
				</tr>
				<tr>
					<td align="right"><?= $keys['recoleccionManual.conf.busqueda.centrales.nombre'] ?></td>
					<td><input type="text" name="nombre" value="<?= htmlentities($nombre) ?>"/></td>
					<td align="right"><?= $keys['recoleccionManual.conf.busqueda.centrales.procesador'] ?></td>
					<td><input type="text" name="procesador" value="<?= htmlentities($procesador) ?>"/></td>
				</tr>
				<tr>
					<td colspan="2" align="right">
						<br/>					
						<span class="botonGrad"><input type="submit" name="btnBuscar" id="btnBuscar" value="<?= $keys['recoleccion.btn.buscar'] ?>" class="boton"/></span>
					</td>
					<td colspan="2" align="left">
						<br/>
						<span class="botonGrad"><a href="?accion=recoleccion_manual_conf&id_recoleccion_manual=<?= $id_recoleccion_manual ?>&nombre_recoleccion_manual=<?= $nombre_recoleccion_manual ?>"><input type="button" value="Limpiar" class="boton"/></a></span>
					</td>				
				</tr>
			</table>
		</fieldset>
	</div>
	
	<!-- FIN BUSQUEDA DE CENTRALES -->
	
	<br>
	<br>
	
	<!-- RESULTADOS BUSQUEDA DE CENTRALES -->
	
	<? if($listado_centrales_resultado_busqueda) { ?>
	<table width="70%" cellspacing="0" cellpadding="2" >
	<tbody>
		<tr align="center">
			<td>
				<?= $listado_centrales_resultado_busqueda ?>
			</td>
		</tr>
		<tr align="center">
			<td>
				<span class="botonGrad"><input type="submit" value="<?= $keys['recoleccionManual.conf.btn.agregar.centrales'] ?>" name="btnAgregarCentrales" id="btnAgregarCentral" class="boton" /></span>
			</td>
		</tr>
	</tbody>
	</table>	
	<? } ?>
	<!-- FIN RESULTADOS BUSQUEDA DE CENTRALES-->
		
	<br>
	
	<!-- CENTRALES ASIGNADAS -->
	
	<? if($listado_centrales) {?>
	<div style="width: 70%;">
		<fieldset>
			<legend>* <?= $keys['recoleccionManual.conf.listado.centrales'] ?></legend>
			<?= $listado_centrales ?>
		</fieldset>
	</div>
	<? } ?>
	
	<!-- FIN CENTRALES ASIGNADAS -->
	
	<br>
	
	<!-- TABLA DE DATOS DE LA RECOLECCION -->	
			
	<table border="0" width="70%">		
		<tr>
			<td>* <?= $keys['recoleccionManual.conf.nombre']?></td>
			<td><input type="text" id="nombre_recoleccion_manual" name="nombre_recoleccion_manual" value="<?= htmlentities($nombre_recoleccion_manual) ?>"/></td>
		</tr>		
	
	</table>
	
	<!-- FIN TABLA DE DATOS DE LA RECOLECCION -->
	
	<br>
	<br>
	<span class="botonGrad"><input type="submit" value="<?= $keys['recoleccionManual.conf.finalizar.configuracion'] ?>" class="boton" name="btnProcesar" /></span>
	<span class="botonGrad"><input type="button" value="<?= $keys['recoleccionManual.btnCancelar'] ?>" class="boton" onClick="javascript: location.href='index.php'; return false;" /></span>
	<br>
	
	<br>
	<div style="display: none;">
		<input type="submit" value="btnRecargar" name="btnRecargar" id="btnRecargar"/>
	</div>
	
</form>
</div>