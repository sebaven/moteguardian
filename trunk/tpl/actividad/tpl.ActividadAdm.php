<div class="cuerpo">
<?= $errores ?>
<?= $mensaje ?>
<br>
<!-- **************************SECCION DE LA ACTIVIDAD************************************ -->
<div align="center">
		
	<!-- ***************************SECCION DE RECOLECCION********************************* -->
	<form method="post" name="atividad_adm">
		<input type="hidden" name="accion" value="actividad_adm" />		
		<input type="hidden" id="id_actividad" name="id_actividad" value=<?= $id_actividad ?> />
		
		<br>
		<table border="0" width="50%">	
		<tr align="center">
			<td align="left"><?= $keys['actividad.select']?></td>
			<td ><input type="text" name="nombre" value="<?= $nombre ?>"/>*</td>			
		</tr>		
		<tr align="center">
			<td align="left"><?= $keys['actividad.filtro']?></td>
			<td ><input type="text" name="filtro" value="<?= $filtro ?>"/>*</td>
		</tr>
		<tr align="center">
			<td align="left"><?= $keys['actividad.recoleccion.formatoRenombrado'] ?></td>
			<td><input type="text" name="formato_renombrado_recoleccion" id="formato_renombrado_recoleccion" value="<?= $formato_renombrado_recoleccion ?>"/>&nbsp;&nbsp;</td>
		</tr>
		</table>		
		<br>			
		<fieldset style="width:50%">
		<legend><?= $keys['seccion.recoleccion']?></legend>
	
			<table border="0" cellspacing="2" cellpadding="0" width="100%">
			<tr>
				<td><?= $keys['select.central']?></td>
				<td>
					<select id="id_central" name="id_central">
					<? ComboControl::Display($comboCentrales, $id_central) ?>
					</select>*
				</td>
			</tr>
			<tr>
				<td><?= $keys['select.plantilla.de.recoleccion']?></td>
				<td>
					<select id="id_plantilla" name="id_plantilla">
					<? ComboControl::Display($comboPlatillaRecoleccion, $id_plantilla) ?>
					</select>*
				</td>				
			</tr>
			</table>
			<br>
			<table border="1" width="100%" cellspacing="0" cellpadding="2" align="left" class="tabla_planificacion">
			<thead>
			<tr>
				<th align="left"><?= $keys['seccion.planificacion']?></th>
				<th class="btn_popup" colspan="1" onclick="agregarPlanificacion(<?= $id_actividad ?>,'actividad')"><img src="imagenes/new.png" /></th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td colspan="2">
					<?= $listado_planificacion ?>
				</td>
				</tr>
			</tbody>
			</table>

	    </fieldset>	
		<br>
		<br>	
    	<!-- ***************************FIN DE SECCION DE RECOLECCION********************************* -->
    	    	
    	<!-- ***************************SECCION DE ENVIOS******************************************** -->		
		<fieldset style="width:50%">		
		<legend><?= $keys['actividad.seccion.envios']?></legend>
			
			<!-- ***************************SECCION AGREGAR ENVIADOR********************************* -->			
			<fieldset>
			<legend>Agregar Enviador</legend>
				<table border="0" align="center" cellpadding="5">
				<tr>
				    <td>
				    	<?= $keys['actividad.seccion.tecnologia']?>
				    </td>
					<td>
						<select id="id_tecnologia_enviador" name="id_tecnologia_enviador">
						<? ComboControl::Display($comboTecnologiaEnviador, $id_tecnologia_enviador) ?>
						</select>*
					</td>
				</tr>
				<tr>
					<td>
						<?= $keys['actividad.seccion.enviador.nombre']?>
					</td>
					<td>
						<input type="text" name="nombre_enviador" id="nombre_enviador"/>*
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="checkbox" name="inmediatamente" id="inmediatamente"/><?= $keys['actividad.seccion.enviador.inmediato']?>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<input type="button" class="boton" value="Configurar enviador" onclick="apretarBotonConfigurarEnviador();"/>
					</td>
				</tr>
				</table>
			</fieldset>
			<br>
			<table width="100%">
			<tbody>
			<? for($cantListados=0;$cantListados < (int)$cantEnvios;$cantListados++) { 
				   $nombreListadoPE='listado_pe_'.$cantListados;
				   $nombreEnviador='nombre_enviador'.$cantListados;
				   $inmediato='inmediato'.$cantListados;
				   $idEnviador='id_enviador'.$cantListados;?>
			
			<!-- *******************************PARA CADA ENVIO*************************************** -->
			<tr><td>
			<table align="left" cellpadding="2" cellspacing="0" border="0">
			<tr>
				<td><?= $$nombreEnviador?></td>
				<td onclick="apretarBotonConfigurarEnviador(<?= $$idEnviador ?>);"><img src="imagenes/b_search.png"  style="cursor:pointer" /></td>
				<td onclick="borrarEnviador(<?= $$idEnviador ?>);"><img src="imagenes/b_drop.png"  style="cursor:pointer"/></td>
			</tr>
			</table>
			</td></tr>
			<tr><td>
			<!-- ***************************SECCION DE PLANIFICACION********************************* -->			
			<table border="1" width="100%" cellspacing="0" cellpadding="2" align="left" class="tabla_planificacion">
			<thead>
			<tr>
				<th align="left"><?= $keys['seccion.planificacion']?></th>
				<th colspan="1" onclick="agregarPlanificacion(<?= $$idEnviador ?>,'enviador')"><img src="imagenes/new.png"  style="cursor:pointer"/></th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td colspan="2">
					<?= $$nombreListadoPE ?>
					<input type="checkbox" name="inmediatamente<?= $$idEnviador ?>"  <?= $$inmediato ?>/><?= $keys['actividad.seccion.enviador.inmediato']?>					
				</td>
			</tr>
			</tbody>			
			</table>
			</td></tr>
			<!-- ***************************FIN PARA CADA ENVIO*************************************** -->
			<?}?>
		</tbody>
		</table>		
		</fieldset>
		
		<!-- ***************************FIN SECCION DE ENVIOS************************************ -->
		
		<br>
		<br>
		<input type="submit" class="boton" value="Guardar Actividad" name="btnProcesar"/>
		<br><br>
				
		<div style="display:none">
			<input type="submit" name="btnRecargar" value="Recargar" id="btnRecargar"/>
			<input type="submit" name="btnConfigurarEnviador" id="btnConfigurarEnviador"/>
			<input type="hidden" name="id_act_env" id="id_act_env" value=""/>				
		</div>
	</form>
</div>

<!-- SCRIPT PARA LLAMAR AL POPUP DE CONFIGURACI�N DE ENVIADOR FTP (Cuando se agrega a uno nuevo)-->
<script type="text/javascript">
	var id_actividad_envio = "<?= $id_actividad_envio ?>";
	var acc_envio = "<?= $acc_envio ?>";		
	if(acc_envio != " ") {		
		if (id_actividad_envio!=""){				
			url = "index.php?accion=<?= $acc_envio ?>&pop=1&idActividadEnvio=<?= $id_actividad_envio ?>&limpiarSession=1&tecnologia=<?= $nombre_tecnologia ?>";
			acc_envio = "";
			id_actividad_envio = "";	
				
			popup("ConfiguracionFiltro",url,'700','500');			 
		}
	} else {
		apretarBotonRecargar();		
	}
</script>


