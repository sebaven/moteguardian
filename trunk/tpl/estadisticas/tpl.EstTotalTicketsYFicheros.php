<h2 align="right"><?=PropertiesHelper::GetKey('estadisticas.total.tickets.y.ficheros')?></h2>
<br>
<?=$errores ?>
<?=$mensaje ?>

<form method="GET" name="est_total_tickets_y_ficheros" action="">
	<input type="hidden" name="accion" value="est_total_tickets_y_ficheros" />
	<fieldset>
		<legend><?= $keys['est.total.tickets.y.ficheros.titulo'] ?></legend><br>
		<br>
		<table border="0" style="width:90%" border="0" align="center">
			<tr>
				<td width="25%">
					<table>
						<tr>
							<td>
								<?= $keys['est.total.tickets.y.ficheros.fecha.desde'] ?>	
							</td>
							<td>				
								<input type="text" id="fecha_desde" name="fecha_desde" readonly="readonly" size="10" value="<?= $fecha_desde ?>" />
							</td>
							<td>
								<button type="button" id="mostrarCalendario_desde">...</button><?= getCalendarDefinition( "fecha_desde", "mostrarCalendario_desde"); ?>
							</td>
						</tr>
						<tr>
							<td>
								<?= $keys['est.total.tickets.y.ficheros.fecha.hasta'] ?>
							</td>
							<td>					
								<input type="text" id="fecha_hasta" name="fecha_hasta" readonly="readonly" size="10" value="<?= $fecha_hasta ?>" />
							</td>
							<td>
								<button type="button" id="mostrarCalendario_hasta">...</button><?= getCalendarDefinition( "fecha_hasta", "mostrarCalendario_hasta"); ?>
							</td>
						</tr>
					</table>
				</td>
				<td align="center">
					<table>
						<tr>
							<td align="left">
								<?= $keys['est.total.tickets.y.ficheros.central'] ?>
							</td>
							<td>
								<select id="id_central" name="id_central"><? ComboControl::Display($options_central, $id_central)?></select>
							</td>
						</tr>
						<tr>
							<td align="left">
								<?= $keys['est.total.tickets.y.ficheros.tec.central'] ?> 
							</td>
							<td>
								<select id="id_tec_central" name="id_tec_central"><? ComboControl::Display($options_tec_central, $id_tec_central)?></select>
							</td>
						</tr>
						<tr>
							<td align="left">
								<?= $keys['est.total.tickets.y.ficheros.destino'] ?> 
							</td>
							<td>
								<select id="id_destino" name="id_destino"><? ComboControl::Display($options_destino, $id_destino)?></select>
							</td>
						</tr>
					</table>	
				</td>
				<td align="center" width="10%">
						<fieldset>
							<legend> <?= $keys['est.total.tickets.y.ficheros.buscar.por'] ?> </legend>
							<table>
								<tr>
									<td>
										<?= $keys['est.total.tickets.y.ficheros.habilitar.busqueda.tickets'] ?>
									</td>
									<td>
										<input type="checkbox" id="habilitar_busqueda_tickets" name="habilitar_busqueda_tickets" checked />
									</td>
								</tr>
								<tr>
									<td>
										<?= $keys['est.total.tickets.y.ficheros.habilitar.busqueda.ficheros'] ?>
									</td>
									<td>
										<input type="checkbox" id="habilitar_busqueda_ficheros" name="habilitar_busqueda_ficheros" checked />
									</td>
								</tr>
							</table>
						</fieldset>
				</td>
				
			</tr>
			<tr>
									
						
			</tr>
		</table>
		<br>
		<div align="center">
			<span class="botonGrad"><input type="submit" name="btnBuscar" value="Buscar" class="boton" /></span>
			&nbsp;&nbsp;<span class="botonGrad"><a href="?accion=est_total_tickets_y_ficheros"><input type="button" value="Limpiar" class="boton"/></a></span>			
		</div>
		<br>
	</fieldset>
</form>
<?= $listadoTickets ?>
<?= $listadoFicheros ?>
