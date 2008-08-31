<h2 align="right"><?= $keys['envioManual.conf.titulo'] ?></h2>
<br>
<?= $errores ?>
<?= $mensaje ?>
<br>
<br>
<div class="app" align="center">
<form method="get" name="envio_manual_conf">
	<input type="hidden" name="accion" value="envio_manual_conf" />		
	<input type="hidden" id="id_envio_manual" name="id_envio_manual" value=<?= $id_envio_manual ?> />
	
	<!-- BUSQUEDA DE FICHEROS -->
	
	<div style="width: 70%;">
		<fieldset>
			<legend><?= $keys['envioManual.conf.busqueda.ficheros'] ?></legend>
			<br/>
			<table width="100%">
				<tr>
					<td><?= $keys['envioManual.conf.busqueda.envio.nombre'] ?></td>
					<td><input type="text" name="nombre_envio" value="<?= htmlentities($nombre_envio) ?>"/></td>
					<td><?= $keys['envioManual.conf.busqueda.ficheros.nombreFichero'] ?></td>
					<td><input type="text" name="nombre_original" value="<?= htmlentities($nombre_original) ?>"/></td>
				</tr>
				<tr>
					<td><?= $keys['envioManual.conf.busqueda.ficheros.tareaDesde'] ?></td>
					<td><input type="text" name="tarea_desde" value="<?= htmlentities($tarea_desde) ?>"/></td>
					<td><?= $keys['envioManual.conf.busqueda.ficheros.tareaHasta'] ?></td>
					<td><input type="text" name="tarea_hasta" value="<?= htmlentities($tarea_hasta) ?>"/></td>
				</tr>
				<tr>
					<td><?= $keys['envioManual.conf.busqueda.envio.fechaVigenciaDesde'] ?></td>
					<td>
						<input type="text" id="fecha_desde" name="fecha_desde" readonly="readonly" size="10" value="<?= $fecha_desde ?>" />
						<button type="button" id="mostrarCalendario_desde">...</button><?= getCalendarDefinition( "fecha_desde", "mostrarCalendario_desde"); ?>
					</td>
					<td><?= $keys['envioManual.conf.busqueda.envio.fechaVigenciaHasta'] ?></td>
					<td>
						<input type="text" id="fecha_hasta" name="fecha_hasta" readonly="readonly" size="10" value="<?= $fecha_hasta ?>" />
						<button type="button" id="mostrarCalendario_hasta">...</button><?= getCalendarDefinition( "fecha_hasta", "mostrarCalendario_hasta"); ?>
					</td>
				</tr>
				<tr>
					<td width="25%"><?= $keys['envioManual.conf.busqueda.ficheros.select.tecnologia.central'] ?></td>
					<td width="25%">
						<select name="id_tecnologia_central">
							<?= ComboControl::Display($tecnologias_central, $id_tecnologia_central); ?>
						</select>
					</td>					
					<td width="25%"><?= $keys['envioManual.conf.busqueda.ficheros.select.tecnologia.envio'] ?></td>
					<td width="25%">
						<select name="id_tecnologia_recolector">
						<?= ComboControl::Display($tecnologias_envio, $id_tecnologia_recolector); ?>
						</select>
					</td>
				</tr>
				<tr>
					<td><?= $keys['envioManual.conf.busqueda.ficheros.nombre'] ?></td>
					<td><input type="text" name="nombre_central" value="<?= htmlentities($nombre_central) ?>"/></td>
					<td><?= $keys['envioManual.conf.busqueda.ficheros.procesador'] ?></td>
					<td><input type="text" name="procesador" value="<?= htmlentities($procesador) ?>"/></td>
				</tr>
				<tr>
					<td colspan="2" align="right">
						<br/>					
						<span class="botonGrad"><input type="submit" name="btnBuscar" id="btnBuscar" value="<?= $keys['envioManual.btn.buscar'] ?>" class="boton"/></span>
					</td>
					<td colspan="2" align="left">
						<br/>
						<span class="botonGrad"><input type="submit" name="btnLimpiar" id="btnLimpiar" class="boton" value="<?= $keys['envioManual.btn.limpiar'] ?>"/></span>
					</td>				
				</tr>
			</table>
		</fieldset>
	</div>
	
	<!-- FIN BUSQUEDA DE FICHEROS -->
	
	<br>

	<!-- RESULTADOS BUSQUEDA DE FICHEROS -->
	
	<? if($listado_ficheros_resultado_busqueda) { ?>
	<table width="70%" cellspacing="0" cellpadding="2" >
	<tbody>
		<tr align="center">
			<td>
				<?= $listado_ficheros_resultado_busqueda ?>
			</td>
		</tr>
		<tr align="center">
			<td>
				<span class="botonGrad"><input type="submit" value="<?= $keys['envioManual.conf.btn.agregar.ficheros'] ?>" name="btnAgregarFicheros" id="btnAgregarFichero" class="boton" /></span>
			</td>
		</tr>
	</tbody>
	</table>	
	<? } ?>
	<!-- FIN RESULTADOS BUSQUEDA DE FICHEROS-->
	
	<br>
	<br>
		
	<!-- FICHEROS ASIGNADOS -->
	
	<? if($listado_ficheros) {?>
	<div style="width: 70%;">
		<fieldset>
			<legend><?= $keys['envioManual.conf.listado.ficheros'] ?>*</legend>
			<?= $listado_ficheros ?>
		</fieldset>
	</div>
	<? } ?>
	
	<!-- FIN FICHEROS ASIGNADOS -->
	
	<br>
	
	<!-- HOSTS DESTINO -->
	<fieldset style="width: 68%">
		<legend><?= $keys['envio.seccion.hosts']?>*</legend>	
		<table align="center" cellpadding="5">
			<tr align="left">
				<td rowspan="2" style="width: 120px;" align="right">Hosts existentes</td>
				<td rowspan="2" style="width: 160px;">
					<select id="hosts" name="hosts[]" multiple="multiple" size="6" style="width: 150px;">
						<? ComboControl::Display($ids_hosts, '') ?>
					</select>
				</td>
				<td style="text-align: center;">
					<button type="button" onclick="agregarHost()"><img src="imagenes/boton_adelante.gif"></button>
				</td>
				<td rowspan="2" style="width: 160px;">
					<select id="hosts_agregados" name="hosts_agregados[]" multiple="multiple" size="6" style="width: 150px;">
						<? ComboControl::Display($ids_hosts_agregados, '') ?>
					</select>
				</td>
				<td rowspan="2" style="width: 120px;">Hosts a los cuales<br>se enviarán los ficheros</td>
		</tr>
		<tr>
			<td style="text-align: center;">
				<button type="button" value="&lt;" onclick="quitarHost();"><img src="imagenes/boton_atras.gif"></button>
			</td>
		</tr>			
		</table>
	</fieldset>
	
	<!-- FIN HOSTS DESTINO -->
	
	<br>
	
	<!-- TABLA DE DATOS DEL ENVIO -->	
			
	<table border="0" width="70%">		
		<tr>
			<td>* <?= $keys['envioManual.conf.nombre']?></td>
			<td><input type="text" id="nombre_envio_manual" name="nombre_envio_manual" value="<?= htmlentities($nombre_envio_manual) ?>"/></td>
		</tr>		
	
	</table>
	
	<!-- FIN TABLA DE DATOS DEL ENVIO -->
	
	<br>
	<br>
	<span class="botonGrad"><input type="submit" value="<?= $keys['envioManual.conf.finalizar.configuracion'] ?>" class="boton" name="btnProcesar" /></span>
	<span class="botonGrad"><input type="button" value="<?= $keys['envioManual.btnCancelar'] ?>" class="boton" onClick="javascript: location.href='index.php'; return false;" /></span>
	<br>
	<br>
	<div style="display: none;">
		<input type="submit" value="btnRecargar" name="btnRecargar" id="btnRecargar"/>
	</div>
	
</form>
</div>

<!-- FUNCIONES PARA SELECCIONAR O DESELECCIONAR LOS HOST-->
<script type="text/javascript">
function agregarHost(){	
	hosts = document.getElementById("hosts");	
	hostsAgregados = document.getElementById("hosts_agregados");

	for(var i=0; i<hosts.options.length; i++) {
		if(hosts.options[i].selected) {
			hostsAgregados.appendChild(hosts.options[i]);
 		}
 	}
}

function quitarHost(){	
	hostsAgregados = document.getElementById("hosts_agregados");	
	hosts = document.getElementById("hosts");

	for (var i=0; i<hostsAgregados.options.length; i++) {
		if (hostsAgregados.options[i].selected) {
			hosts.appendChild(hostsAgregados.options[i]);
		}
	}
}
</script>