<h2 align="right"><?= $keys['actividad.conf.titulo'] ?></h2>
<br>
<?= $errores ?>
<?= $mensaje ?>
<div align="center" class="app">
<form method="post" name="atividad_new">
	<input type="hidden" name="accion" value="actividad_new" />		
	<input type="hidden" id="id_actividad" name="id_actividad" value=<?= $id_actividad ?> />

	<!-- DATOS DE LA ACTIVIDAD -->

	<table width="50%">	
	<tr>
		<td width="50%">* <?= $keys['actividad.conf.nombre']?></td>
		<td width="50%"><input type="text" name="nombre_actividad" value="<?= htmlentities($nombre_actividad) ?>"/></td>			
	</tr>
	<tr>
		<td>* <?= $keys['select.plantilla.de.recoleccion']?></td>
		<td>
			<select id="id_plantilla" name="plantilla">
				<? ComboControl::Display($plantillas, $id_plantilla); ?>
			</select>
		</td>			
	</tr>
	</table>
	
	<!-- FIN DATOS DE LA ACTIVIDAD -->


	
	<!-- RECOLECCIONES -->
	<fieldset style="width: 70%">
			<legend>* <?= $keys['actividad.conf.recolecciones.titulo']?></legend>	
			<table align="center" border="0" cellpadding="5" width="100%">
				<tr>
					<td align="center" width="45%"><?= $keys['actividad.conf.recolecciones.ofrecidas'] ?></td>
					<td width="10%"></td>
					<td align="center" width="45%"><?= $keys['actividad.conf.recolecciones.seleccionadas'] ?></td>
				<tr>					
					<td rowspan="2" align="center">
						<select id="recolecciones" name="recolecciones[]" multiple="multiple" size="6">
							<? ComboControl::Display($ids_recolecciones, '') ?>
						</select>
					</td>
					<td align="center">
						<button type="button" onclick="agregarRecoleccion()"><img src="imagenes/boton_adelante.gif"></button>
					</td>
					<td rowspan="2" align="center">
						<select id="recolecciones_agregadas" name="recolecciones_agregadas[]" multiple="multiple" size="6">
							<? ComboControl::Display($ids_recolecciones_agregadas, '') ?>
						</select>
					</td>					
				</tr>
			<tr>
				<td align="center">
					<button type="button" value="&lt;" onclick="quitarRecoleccion();"><img src="imagenes/boton_atras.gif"></button>
				</td>
			</tr>			
			</table>
		</fieldset>
	
	
	<!-- FIN RECOLECCIONES -->
	<br>
	<br>
	<span class="botonGrad"><input type="submit" class="boton" value="Guardar Actividad" name="btnProcesar"/></span>
	<br/>
	<br/>
</form>
</div>

<!-- FUNCIONES PARA SELECCIONAR O DESELECCIONAR LOS HOST-->

<script type="text/javascript">

	function agregarRecoleccion(){	
		recolecciones = document.getElementById("recolecciones");	
		recoleccionesAgregadas = document.getElementById("recolecciones_agregadas");
	
		for(var i=0; i<recolecciones.options.length; i++) {
			if(recolecciones.options[i].selected) {
				recoleccionesAgregadas.appendChild(recolecciones.options[i]);
 			}
 		}
	}
	
	function quitarRecoleccion(){	
		recoleccionesAgregadas = document.getElementById("recolecciones_agregadas");	
		recolecciones = document.getElementById("recolecciones");
	
		for (var i=0; i<recoleccionesAgregadas.options.length; i++) {
			if (recoleccionesAgregadas.options[i].selected) {
				recolecciones.appendChild(recoleccionesAgregadas.options[i]);
			}
		}
	}
	
</script>
