<h2 align="right"><?= $keys['visualizacion.circuitos.titulo'] ?></h2>
<br>
<?= $errores ?>
<?= $mensaje ?>
<br/>
<div class="app" align="center">
<form method="POST" name="visualizacion_circuitos" action="">
<input type="hidden" name="accion" value="visualizacion_circuitos" />	
		
		<fieldset style="width: 90%;">	
		<legend><?= $keys['visualizacion.circuitos.busqueda'] ?></legend>
				
		<table width="100%">
			<tr>
				<td width="25%"><?= $keys['visualizacion.circuitos.busqueda.central'] ?></td>
				<td width="25%"><input type="text" name="central" value="<?= htmlentities($central) ?>"/></td>			
				<td width="25%"><?= $keys['visualizacion.circuitos.busqueda.procesador'] ?></td>			
				<td width="25%"><input type="text" name="procesador" value="<?= htmlentities($procesador) ?>"/></td>			
			</tr>
			<tr>
				<td><?= $keys['visualizacion.circuitos.busqueda.tecnologia.central'] ?></td>
				<td><select name="id_tecnologia_central"><? ComboControl::Display($tecnologias_central, $id_tecnologia_central); ?></select></td>
				<td><?= $keys['visualizacion.circuitos.busqueda.tecnologia.recolector'] ?></td>
				<td><select name="id_tecnologia_recolector"><? ComboControl::Display($tecnologias_recolector, $id_tecnologia_recolector); ?></select></td>
			</tr>
			<tr>
				<td><br/><?= $keys['visualizacion.circuitos.busqueda.recoleccion'] ?></td>
				<td><br/><input type="text" name="recoleccion" value="<?= htmlentities($recoleccion) ?>"/></td>
				<td><br/></td>
				<td><br/></td>
			</tr>
			<tr>
				<td><br/><?= $keys['visualizacion.circuitos.busqueda.actividad'] ?></td>
				<td><br/><input type="text" name="actividad" value="<?= htmlentities($actividad) ?>"/></td>
				<td><br/><?= $keys['visualizacion.circuitos.busqueda.plantilla'] ?></td>
				<td><br/><input type="text" name="plantilla" value="<?= htmlentities($plantilla) ?>"/></td>
			</tr>
			<tr>
				<td><br/><?= $keys['visualizacion.circuitos.busqueda.envio'] ?></td>
				<td><br/><input type="text" name="envio" value="<?= htmlentities($envio) ?>"/></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td><?= $keys['visualizacion.circuitos.busqueda.host'] ?></td>
				<td><input type="text" name="host" value="<?= htmlentities($host) ?>"/></td>
				<td><?= $keys['visualizacion.circuitos.busqueda.tecnologia.enviador'] ?></td>
				<td><select name="id_tecnologia_enviador"><? ComboControl::Display($tecnologias_recolector, $id_tecnologia_enviador); ?></select></td>
			</tr>
			<tr><td></td></tr>
			<tr>
				<td colspan="4">
					<fieldset>
						<legend><?= $keys['visualizacion.circuitos.busqueda.completitud.circuitos'] ?></legend>
						
						<table align="center">
							<tr>
								<td align="right"><input type="checkbox" name="centrales_no_asignadas" <?= $centrales_no_asignadas_checked ?>/></td>
								<td><?= $keys['visualizacion.circuitos.busqueda.centrales.no.asignadas'] ?></td>
								<td width="20%"></td>
								<td align="right"><input type="checkbox" name="hosts_no_asignados" <?= $hosts_no_asignados_checked ?>/></td>
								<td><?= $keys['visualizacion.circuitos.busqueda.hosts.no.asignados'] ?></td>								
							</tr>
							<tr>
								<td align="right"><input type="checkbox" name="plantillas_no_asignadas" <?= $plantillas_no_asignadas_checked ?>/></td>
								<td><?= $keys['visualizacion.circuitos.busqueda.plantillas.no.asignadas'] ?></td>
								<td width="20%"></td>
								<td align="right"><input type="checkbox" name="circuitos_completos" <?= $circuitos_completos_checked ?>/></td>
								<td><?= $keys['visualizacion.circuitos.busqueda.completos'] ?></td>								
							</tr>
							<tr>
								<td align="right"><input type="checkbox" name="recolecciones_sin_act_env" <?= $recolecciones_sin_act_env_checked ?>/></td>
								<td><?= $keys['visualizacion.circuitos.busqueda.recolecciones.sin.act.env'] ?></td>
								<td width="20%"></td>
								<td align="right"></td>
								<td></td>								
							</tr>			
						</table>
					
					</fieldset>
				</td>
			</tr>
			
			<tr>
				<td colspan="2" align="right"><br/><span class="botonGrad"><input type="submit" name="btnBuscar" class="boton" value="<?= $keys['visualizacion.circuitos.boton.buscar'] ?>" /></span></td>
				<td colspan="2" align="left"><br/><span class="botonGrad"><a href="?accion=visualizacion_circuitos"><input type="button" value="<?= $keys['visualizacion.circuitos.boton.limpiar'] ?>" class="boton"/></a></span></td>
			</tr>
		</table>
		
		</fieldset>	
		
		<br/>
		<br/>
		
		<? if($cantidad_circuitos==0){

			echo $keys['visualizacion.circuitos.no.hay.circuitos']."<br/>";
			
		}?>	
		
		<div class="tabla_circuitos"/>
			
			<?			
			for($i_circuito = 0; $i_circuito < $cantidad_circuitos; $i_circuito++) {
				$nombre_var_recoleccion = "recoleccion_".$i_circuito;
				$nombre_var_plantilla = "plantilla_".$i_circuito;
				$nombre_var_id_plantilla = "id_plantilla_".$i_circuito;
				$nombre_var_id_recoleccion = "id_recoleccion_".$i_circuito;
				$nombre_var_habilitado_checked = "habilitado_checked_".$i_circuito;
				$nombre_var_cantidad_centrales = "cantidad_centrales_".$i_circuito;
				$nombre_var_cantidad_planificaciones = "cantidad_planificaciones_".$i_circuito;
				$nombre_var_cantidad_filtros = "cantidad_filtros_".$i_circuito;
				$nombre_var_cantidad_envios = "cantidad_envios_".$i_circuito;
			?>
			
			
			<!-- PARA CADA CIRCUITO -->			
			<table class="encabezado" width="95%" cellpadding="5" cellspacing="0">
				<tr>
					<td width="20%"> Centrales </td>
					<td width="30%"> Recolecci&oacute;n: <?= htmlentities($$nombre_var_recoleccion) ?> <? if($$nombre_var_recoleccion != 'no asignada'){ ?><img src="imagenes/editar.png" title="Editar recolecci&oacute;n..." onclick="editarRecoleccion('<?= $$nombre_var_id_recoleccion ?>')" /><?} if($$nombre_var_habilitado_checked!='no asignado'){?> <input type="checkbox"  disabled name="habilitado_recoleccion_<?= $$nombre_var_id_recoleccion ?>" <?= $$nombre_var_habilitado_checked ?>/><?}?></td>
					<td width="20%"> Plantilla: <?= htmlentities($$nombre_var_plantilla) ?> <? if($$nombre_var_plantilla != 'no asignada'){ ?><img src="imagenes/editar.png" title="Editar plantilla..." onclick="seleccionarPlantilla('<?= $$nombre_var_id_plantilla ?>')" /><?} ?> </td>					
					<td width="25%"> Env&iacute;os </td>
					<td width="5%" align="center"> <img src="imagenes/b_search.png" onclick="mostrarDetalles('<?= $i_circuito ?>')" title="mostrar detalles..."/> </td>					
				</tr>
			</table>
			
			<div id="detalles_<?= $i_circuito ?>" style="display: none; width: 100%;">
			
			<table class="detalles" width="95%">
				<tr>				
					<td width="20%" valign="middle">
					
					
						<!-- LISTADO DE CENTRALES -->
						<?					
						for($i_central = 0; $i_central < $$nombre_var_cantidad_centrales; $i_central++) {
							$nombre_var_nombre_central = "nombre_central_".$i_circuito."_".$i_central;
							$nombre_var_procesador_central = "procesador_central_".$i_circuito."_".$i_central;
							$nombre_var_id_central = "id_central_".$i_circuito."_".$i_central;												
						?>
							<?= $$nombre_var_nombre_central ?> <? if($$nombre_var_id_central != 'no asignado'){ ?><img src="imagenes/editar.png" onclick="seleccionarCentral(<?= $$nombre_var_id_central ?>)" /> <? } ?> - <?= $$nombre_var_procesador_central ?> <br/> 
						<?
						}
						?>
						<!-- FIN LISTADO CENTRALES -->
						
						
					</td>
					<td width="30%" valign="middle">
						
						
						<!--  LISTADO DE PLANIFICACIONES DE LA RECOLECCION -->
						<?
						for($i_planificacion = 0; $i_planificacion < $$nombre_var_cantidad_planificaciones; $i_planificacion++) {
							$nombre_var_planificacion = "planificacion_".$i_circuito."_".$i_planificacion;								
						?>
							- <?= htmlentities($$nombre_var_planificacion) ?> <br/>
						<?
						}	
						?>
						<!-- FIN LISTADO DE PLANIFICACIONES DE LA RECOLECCION -->
						
						
					</td>
					<td width="20%" valign="middle">
						
						
						<!-- LISTADO FILTROS -->
						<?
						for($i_filtro = 0; $i_filtro < $$nombre_var_cantidad_filtros; $i_filtro++ ) {
							$nombre_var_filtro = "filtro_".$i_circuito."_".$i_filtro;
						?>
							- <?= htmlentities($$nombre_var_filtro) ?> <br/>
						<?
						}
						?>
						<!-- FIN LISTADO FILTROS -->
						
						
					</td>
					<td width="25%" valign="middle" align="center">						
						
						<!-- LISTADO ENVIADORES -->
						<?
						for($i_envio = 0; $i_envio < $$nombre_var_cantidad_envios; $i_envio++ ) {
							$nombre_var_nombre_envio = "envio_".$i_circuito."_".$i_envio;
							$nombre_var_id_envio = "id_envio_".$i_circuito."_".$i_envio;
							$nombre_var_habilitado_envio_checked = "habilitado_envio_checked_".$i_circuito."_".$i_envio;

							$envio = "- ".htmlentities($$nombre_var_nombre_envio)." ";
													 
							if($$nombre_var_habilitado_envio_checked!='no asignado'){
								// Agrego el lapiz para acceder a la edición	
								$envio .= "<img src='imagenes/editar.png' title='Editar envio...' onclick='editarEnvio(".$$nombre_var_id_envio.")' /> ";
								// Agrego el checkbox que dice si está habilitado
								$envio .= "<input type='checkbox' name='envio_habilitado_'".$$nombre_var_id_envio." disabled ".$$nombre_var_habilitado_envio_checked."/> ";
							}

							echo $envio;								
						}
						?>
						<!-- FIN LISTADO ENVIADORES -->
						
						
					</td>
					<td width="5%" valign="middle">
						
						
						<!-- BOTON EJECUTAR AHORA -->
						<? if($$nombre_var_habilitado_checked!='no asignado'){?><img src="imagenes/boton_adelante.gif" title="ejecutar circuito ahora..." onclick="ejecutarRecoleccionAhora(<?= $$nombre_var_id_recoleccion ?>)"/><?}?>
						<!-- FIN BOTON EJECUTAR AHORA -->
						
						
					</td>
				</tr>
			</table>
			</div>
			
			<!--  FIN PARA CADA CIRCUITO  -->
			
			
			<?
			} 
			?>		
		</div>
		<br/>
		<br/>
		
		<!-- HOSTS NO ASIGNADOS -->
				
		<div style="width: 50%;" align="center">
		<? if($hosts_no_asignados_checked){ ?><h2>Hosts No asignados</h2><?} ?>
		<?= $listado ?>
		</div>		
		
		<!-- FIN HOSTS NO ASIGNADOS -->
		<br/>
		<br/>		
</form>
</div>