<h2 align="right"><?=PropertiesHelper::GetKey('estadisticas.intentos.recoleccion.envios')?></h2>
<br>
<?=$errores ?>
<?=$mensaje ?>

<form method="GET" name="est_intentos_recoleccion_y_envios" action="">
	<input type="hidden" name="accion" value="est_intentos_recoleccion_y_envios" />
	<fieldset>
		<legend><?= $keys['est.intentos.recoleccion.envios.titulo'] ?></legend><br>
		<br>
		<table border="0" style="width:100%" border="0">
			
			<!-- Día -->
			<tr>
				<td align="left">
					<?= $keys['est.intentos.recoleccion.envios.dia'] ?>					
					<input type="text" id="fecha" name="fecha" readonly="readonly" size="10"/>
					<button type="button" id="mostrarCalendario">...</button><?= getCalendarDefinition( "fecha", "mostrarCalendario"); ?>
				</td>
				<td align="left" width="40%">
						<fieldset>
							<legend> <?= $keys['est.intentos.recoleccion.envios.recolecciones'] ?> </legend>
							
							<?= $keys['est.intentos.recoleccion.habilitar.busqueda.recoleccion'] ?> 
							<input type="checkbox" id="habilitar_busqueda_recoleccion" name="habilitar_busqueda_recoleccion" checked />
							<br/><br/>
							
							<div id="busqueda_recoleccion">
								<input type="radio" name="tipo_recoleccion" value="automatica"> <?= $keys['est.intentos.recoleccion.envios.tipo.recoleccion.auto']?> <br>
								<input type="radio" name="tipo_recoleccion" value="manual"><?= $keys['est.intentos.recoleccion.envios.tipo.recoleccion.manual']?> <br>
								<input type="radio" name="tipo_recoleccion" value="ambos" checked> <?= $keys['est.intentos.recoleccion.envios.tipo.recoleccion.ambos']?> <br>
								
								<br/>
								<?= $keys['est.intentos.recoleccion.tec.recolector'] ?> <select id="id_tec_recolector" name="id_tec_recolector"><? ComboControl::Display($options_tec_recolector, $id_tec_recolector)?></select>
							</div>
						</fieldset>

					
				</td>
				<td align="left" width="40%">
						<fieldset>
							<legend> <?= $keys['est.intentos.recoleccion.envios.envios'] ?> </legend>
							
							<?= $keys['est.intentos.recoleccion.habilitar.busqueda.envio'] ?> 
							<input type="checkbox" id="chk_habilitar_busqueda_envio" name="habilitar_busqueda_envio" checked />
							<br/><br/>
							
							<div id="busqueda_envio">
								<input type="radio" name="tipo_envio" value="automatica"> <?= $keys['est.intentos.recoleccion.envios.tipo.envio.auto']?> <br>
								<input type="radio" name="tipo_envio" value="manual"><?= $keys['est.intentos.recoleccion.envios.tipo.envio.manual']?> <br>
								<input type="radio" name="tipo_envio" value="ambos" checked> <?= $keys['est.intentos.recoleccion.envios.tipo.envio.ambos']?> <br>
								
								<br/>
								<?= $keys['est.intentos.envio.tec.enviador'] ?> <select id="id_tec_enviador" name="id_tec_enviador"><? ComboControl::Display($options_tec_enviador, $id_tec_enviador)?></select>
							</div>
						</fieldset>


				</td>
				
			</tr>
			<tr>
									
						
			</tr>
		</table>
		<br>
		<div align="center">
			<span class="botonGrad"><input type="submit" name="btnBuscar" value="Buscar" class="boton" /></span>
			&nbsp;&nbsp;<span class="botonGrad"><a href="?accion=est_intentos_recoleccion_y_envios"><input type="button" value="Limpiar" class="boton"/></a></span>			
		</div>
		<br>
	</fieldset>
</form>
<?= $listadoRecolecciones ?>
<?= $listadoEnvios ?>
