<h2 align="right"><?= $keys['recoleccion.conf.titulo'] ?></h2>
<br>
<?= $errores ?>
<?= $mensaje ?>
<div align="center" class="app">
<form method="post" name="recoleccion_conf">
	<input type="hidden" name="accion" value="recoleccion_conf" />		
	<input type="hidden" id="id_recoleccion" name="id_recoleccion" value=<?= htmlentities($id_recoleccion) ?> />
	
	<!-- TABLA DE DATOS DE LA RECOLECCION -->	
			
	<table border="0" width="70%">	
		<tr>
			<td width="50%" align="right">* <?= $keys['recoleccion.conf.nombre']?></td>
			<td><input type="text" name="nombre_recoleccion" value="<?= htmlentities($nombre_recoleccion) ?>"/></td>			
		</tr>		
		<tr>
			<td align="right"><?= $keys['recoleccion.conf.habilitado']?></td>
			<td><input type="checkbox" name="habilitado_recoleccion" <?= $habilitado_recoleccion_checked ?> /></td>
		</tr>		
	</table>
	
	<!-- FIN TABLA DE DATOS DE LA RECOLECCION -->
	
	<br/>
	
	<!-- PLANIFICACIONES -->
		
	<table width="70%" cellspacing="0" cellpadding="2" class="tabla_planificacion">
	<thead>
		<tr>
			<th align="left"><?= $keys['seccion.planificacion']?></th>
			<th class="btn_popup" colspan="1" onclick="agregarPlanificacion(<?= htmlentities($id_recoleccion) ?>,'recoleccion')"><img src="imagenes/new.png"/></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td colspan="2">
				<?= $listado_planificaciones ?>
			</td>
		</tr>
	</tbody>
	</table>
	
	<!-- FIN PLANIFICACIONES -->
	
	<br/>
	
	<!-- CENTRALES ASIGNADAS -->
	
	<? if($listado_centrales) {?>
	<div style="width: 70%;">
		<fieldset>
			<legend><?= $keys['recoleccion.conf.listado.centrales'] ?></legend>
			<?= $listado_centrales ?>
		</fieldset>
	</div>
	<? } ?>
	
	<br/>
	
	<span class="botonGrad"><input type="submit" value="<?= $keys['recoleccion.conf.finalizar.configuracion'] ?>" class="boton" name="btnProcesar" /></span>
	
	<br/>
	
	<!-- FIN CENTRALES ASIGNADAS -->
	
	<br/>
	<br/>
	<br/>
	
	<!-- BUSQUEDA DE CENTRALES -->
	
	<div style="width: 70%;">
		<fieldset>
			<legend><?= $keys['recoleccion.conf.busqueda.centrales'] ?></legend>
			<br/>
			<table width="100%">
				<tr>
					<td width="25%" align="right"><?= $keys['recoleccion.conf.busqueda.centrales.select.tecnologia.central'] ?></td>
					<td width="25%">
						<select name="tecnologia_central_busqueda" id="tecnologia_central_busqueda">
							<?= ComboControl::Display($tecnologias_central, $id_tecnologia_central_seleccionada); ?>
						</select>
					</td>					
					<td width="25%" align="right"><?= $keys['recoleccion.conf.busqueda.centrales.select.tecnologia.recoleccion'] ?></td>
					<td width="25%">
						<select name="tecnologia_recoleccion_busqueda" id="tecnologia_recoleccion_busqueda">
						<?= ComboControl::Display($tecnologias_recoleccion, $id_tecnologia_recoleccion_seleccionada); ?>
						</select>
					</td>
				</tr>
				<tr>
					<td align="right"><?= $keys['recoleccion.conf.busqueda.centrales.nombre'] ?></td>
					<td><input type="text" name="nombre_central_busqueda" value="<?= htmlentities($nombre_central_busqueda) ?>"/></td>
					<td align="right"><?= $keys['recoleccion.conf.busqueda.centrales.procesador'] ?></td>
					<td><input type="text" name="procesador_central_busqueda" value="<?= htmlentities($procesador_central_busqueda) ?>"/></td>
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
	</div>
	
	<!-- FIN BUSQUEDA DE CENTRALES -->
	
	<br/>
	<br/>
	
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
				<span class="botonGrad"><input type="submit" value="<?= $keys['recoleccion.conf.btn.agregar.centrales'] ?>" name="btnAgregarCentrales" id="btnAgregarCentral" class="boton" /></span>
			</td>
		</tr>
	</tbody>
	</table>	
	<? } ?>
	<!-- FIN RESULTADOS BUSQUEDA DE CENTRALES-->
	
	<br/>
	<div style="display: none;">
		<input type="submit" value="btnRecargar" name="btnRecargar" id="btnRecargar"/>
	</div>
	
</form>
</div>