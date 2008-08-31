<h2 align="right"><?= $keys['tecnologia.central.admin.titulo'] ?></h2>
<br/>

<?=$errores ?>
<?=$mensaje ?>

<form method="POST" name="tecnologia_central_admin">
	<input type="hidden" name="accion" value="tecnologia_central_admin"/>
	<input type="hidden" id="id_tecnologia_central" name="id_tecnologia_central" value="<?= $id_tecnologia_central ?>" />
	<input type="hidden" id="accion_tecnologia_central" value="<?= $accion_tecnologia_central ?>" name="accion_tecnologia_central"/>	
	<br/>
	
	<!-- FIELDSET DE CREACION DE TECNOLOGIAS DE CENTRALES -->
		
	<fieldset>
		<legend><?= $keys['tecnologia.central.admin.fieldset.creacion'] ?></legend>
		<br/>
		<table width="100%">
			<tr align="center">
				<td colspan="2">
					<span class="botonGrad"><input type="button" class="boton" value="Crear nueva tecnolog&iacute;a" onclick="mostrarCrearTecnologia();"/></span>
					<span class="botonGrad"><input type="button" class="boton" value="Agregar nueva versi&oacute;n de tecnolog&iacute;a" onclick="mostrarAgregarVersion();"/></span>
					<br/><br/>					 
				</td>
			</tr>
		</table>
		
		<!-- DIV QUE SE MUESTRA SI SE CREA UNA TECNOLOGIA -->
		
		<div id="creacion_tecnologia" style="display: none;">
			<table width="100%">
				<tr>
					<td width="20%" align="right">* <?= $keys['tecnologia.central.creacion.nombre'] ?></td>
					<td><input type="text" value="<?= htmlentities($nombre_nueva_tecnologia) ?>" name="nombre_nueva_tecnologia" id="nombre_nueva_tecnologia" size="30"/></td>
				</tr>				
				<tr>
					<td width="20%" align="right">* <?= $keys['tecnologia.central.creacion.version'] ?></td>
					<td><input type="text" value="<?= htmlentities($version_nueva_tecnologia) ?>" name="version_nueva_tecnologia" id="version_nueva_tecnologia" size="7"/></td>
				</tr>				
				<tr>
					<td width="20%" align="right"><?= $keys['tecnologia.central.creacion.descripcion'] ?></td>
					<td><textarea name="descripcion_nueva_tecnologia" id="descripcion_nueva_tecnologia" rows="3" cols="50"><?= htmlentities($descripcion_nueva_tecnologia) ?></textarea></td>
				</tr>				
				<tr>
					<td align="center" colspan="2">
						<span class="botonGrad"><input type="submit" name="btnProcesar" value="Guardar" class="boton"/></span>
						<span class="botonGrad"><a href="?accion=tecnologia_central_admin"><input type="button" class="boton" value="Cancelar"/></a></span>
					</td>
				</tr>
			</table>
		</div>
		
		<!-- DIV QUE SE MUESTRA SI SE CREA UNA VERSION DE UNA TECNOLOGIA EXISTENTE-->
		
		<div id="agregar_version" style="display: none;">
			<table width="100%">
				<tr>
					<td width="20%" align="right">* <?= $keys['tecnologia.central.creacion.nombre'] ?></td>
					<td>
						<select name="nombre_nueva_version" id="nombre_nueva_version" ><? ComboControl::Display($combo_nombre_tecnologia_central, $nombre_nueva_version) ?></select>
					</td>
				</tr>				
				<tr>
					<td width="20%" align="right">* <?= $keys['tecnologia.central.creacion.version'] ?></td>
					<td><input type="text" value="<?= htmlentities($version_nueva_version) ?>" name="version_nueva_version" id="version_nueva_version" size="7"/></td>
				</tr>				
				<tr>
					<td width="20%" align="right"><?= $keys['tecnologia.central.creacion.descripcion'] ?></td>
					<td><textarea name="descripcion_nueva_version" id="descripcion_nueva_version" rows="3" cols="50"><?= htmlentities($descripcion_nueva_version) ?></textarea></td>
				</tr>				
				<tr>
					<td align="center" colspan="2">
						<span class="botonGrad"><input type="submit" name="btnProcesar" value="Guardar" class="boton"/></span>
						<span class="botonGrad"><a href="?accion=tecnologia_central_admin"><input type="button" class="boton" value="Cancelar"/></a></span>
					</td>
				</tr>
			</table>
		</div>
	</fieldset>
</form>

<!-- FIELDSET DE MODIFCACION DE TECNOLOGIAS DE CENTRALES -->
	
<fieldset>
	<legend><?= $keys['tecnologia.central.admin.fieldset.modificacion'] ?></legend>
	<br/>
	
	<?= $listado_tecnologias_central ?>
	
	<br/>
	<br/>

</fieldset>
<script type="text/javascript">
	accionTecnologiaCentral = document.getElementById('accion_tecnologia_central');
	if( accionTecnologiaCentral.value=='ninguna' ){
	} else if ( accionTecnologiaCentral.value=='agregarVersion' ) {
		mostrarAgregarVersion();
	} else if ( accionTecnologiaCentral.value=='crearTecnologia' ) {
		mostrarCrearTecnologia();
	}
	
</script>
