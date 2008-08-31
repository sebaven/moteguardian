<h2 align="right"><?= $keys['envio.conf.titulo'] ?></h2><br/>
<br>
<?= $errores ?>
<?= $mensaje ?>

<div align="center" class="app">
	<form method="post" name="envio_adm">
		<input type="hidden" name="accion" value="envio_adm" />		
		<input type="hidden" id="id_envio" name="id_envio" value=<?= htmlentities($id_envio) ?> />	
		
		<!-- ******************* DATOS DEL ENVIO ******************** -->
				
		<table width="70%">		
		<tr>
			<td width="50%">* <?= $keys['envio.conf.nombre']?></td>
			<td><input type="text" name="nombre_envio" value="<?= htmlentities($nombre_envio) ?>"/></td>			
		</tr>				
		<tr>
			<td><?= $keys['envio.conf.habilitado']?></td>
			<td><input type="checkbox" name="habilitado_envio" <?= $habilitado_envio_checked ?> /></td>
		</tr>	
		<tr>
			<td><?= $keys['envio.conf.inmediato']?></td>
			<td><input type="checkbox" name="inmediato_envio" <?= $inmediato_envio_checked ?> /></td>
		</tr>	
		</table>				
		
		<!-- ******************* FIN DATOS DEL ENVIO ******************** -->
		
		<br/>	
		
		<!-- ******************* PLANIFICACIONES ******************** -->
		
		<table width="70%" cellspacing="0" cellpadding="2" class="tabla_planificacion">
		<thead>
			<tr>
				<th align="left"><?= $keys['seccion.planificacion']?></th>
				<th class="btn_popup" colspan="1" onclick="agregarPlanificacion(<?= $id_envio ?>,'envio')"><img src="imagenes/new.png"/></th>
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
		
		<!-- ******************* FIN PLANIFICACIONES ******************** -->
		
		<br/>
		
		<!-- ******************* HOSTS ******************** -->
		
		<fieldset style="width: 70%">
			<legend>* <?= $keys['envio.seccion.hosts']?></legend>	
			<table align="center" cellpadding="5">
				<tr align="left">
					<td rowspan="2" style="width: 120px;" align="right">Hosts existentes</td>
					<td rowspan="2" style="width: 160px;">
						<select id="hosts[]" name="hosts[]" multiple="multiple" size="6">
							<? ComboControl::Display($ids_hosts, '') ?>
						</select>
					</td>
					<td style="text-align: center;">
						<button type="button" onclick="agregarHost()"><img src="imagenes/boton_adelante.gif"></button>
					</td>
					<td rowspan="2" style="width: 160px;">
						<select id="hosts_agregados[]" name="hosts_agregados[]" multiple="multiple" size="6">
							<? ComboControl::Display($ids_hosts_agregados, '') ?>
						</select>
					</td>
					<td rowspan="2" style="width: 120px;">Hosts a los cuales<br>se enviar&aacute;n los ficheros</td>
			</tr>
			<tr>
				<td style="text-align: center;">
					<button type="button" value="&lt;" onclick="quitarHost();"><img src="imagenes/boton_atras.gif"></button>
				</td>
			</tr>			
			</table>
		</fieldset>
	
		<!-- ******************* FIN HOSTS ******************** -->
		
		<br/>
		
		<!-- ******************* RECOLECCIONES ******************** -->
		
		<fieldset style="width: 70%">
			<legend>* <?= $keys['envio.seccion.recolecciones']?></legend>
			<table>				
			<tr>
				<td width="25%"><?= $keys['envio.seccion.recolecciones.recoleccion'] ?></td>
				<td>
					<select name="recoleccion" id="recoleccion" onchange="apretarBotonRecargar();">
						<?= ComboControl::Display($opciones_recolecciones, $id_recoleccion_seleccionada); ?>
					</select>
				</td>
				<td style="width: 120px;" align="right">Centrales de la recolecci&oacute;n</td>
				<td style="width: 160px;">
					<select id="id_central" name="id_central" disabled="disabled" size="6" style="width: 150px;">
						<? ComboControl::Display($centrales_de_la_recoleccion, '' ) ?>
					</select>
				</td>
			</tr>			
			</table>		
		</fieldset>
	
		<!-- ******************* FIN RECOLECCIONES ******************** -->
		
		<br/>
		<br/>
		<span class="botonGrad"><input type="submit" class="boton" value="Guardar Env&iacute;o" name="btnProcesar"/><br/></span>
		<br/>
				
		<div style="display:none">
			<input type="submit" name="btnRecargar" value="Recargar" id="btnRecargar"/>				
		</div>
		
	</form>
</div>

<!-- FUNCIONES PARA SELECCIONAR O DESELECCIONAR LOS HOST-->
<script type="text/javascript">
function agregarHost(){	
	hosts = document.getElementById("hosts[]");	
	hostsAgregados = document.getElementById("hosts_agregados[]");

	for(var i=0; i<hosts.options.length; i++) {		
		if(hosts.options[i].selected) {
			hostsAgregados.appendChild(hosts.options[i]);
 		}
 	}
}

function quitarHost(){	
	hostsAgregados = document.getElementById("hosts_agregados[]");	
	hosts = document.getElementById("hosts[]");

	for (var i=0; i<hostsAgregados.options.length; i++) {
		if (hostsAgregados.options[i].selected) {
			hosts.appendChild(hostsAgregados.options[i]);
		}
	}
}
</script>