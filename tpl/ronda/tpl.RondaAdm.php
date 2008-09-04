<h2 align="right">Administrar rondas</h2>
<br>
<?=$errores ?>
<?=$mensaje ?>

<form method="GET" name="ronda_adm" action="">
	<input type="hidden" name="accion" value="ronda_adm" />
		
		
	<fieldset>	
		<legend>Datos de la ronda</legend><br>
		<br>
		
		
		<table width="70%" align="center" border="0">
		<tr>
			<td align="right" width="25%">Guardia</td>
			<td>
				<select name="id_guardia" style="width: 290px">
	                <? ComboControl::Display($options_guardias, $id_guardia)?>
	              </select>            
			</td>
		</tr>	
		<tr height="15px">
			<td colspan="2"></td>
		</tr>				
		<tr>
			<td rowspan="2" align="right">Fecha de b&uacute;squeda</td>
			<td align="center" width="40%">
				Desde					
				<input type="text" id="fecha_desde" name="fecha_desde" readonly="readonly" size="10" value="<?= $fecha_desde ?>" />
				<button type="button" id="mostrarCalendario_desde">...</button><?= getCalendarDefinition( "fecha_desde", "mostrarCalendario_desde"); ?>
				<select id="horas_desde" name="horas_desde" ><? ComboControl::Display($options_horas_desde, $id_horas_desde)?></select> :
				<select id="minutos_desde" name="minutos_desde" ><? ComboControl::Display($options_minutos_desde, $id_minutos_desde)?></select>
			</td>					
		</tr>			
		<tr>
			<td align="center">
				Hasta		
				<input type="text" id="fecha_hasta" name="fecha_hasta" readonly="readonly" size="10" value="<?= $fecha_hasta ?>" />
				<button type="button" id="mostrarCalendario_hasta">...</button><?= getCalendarDefinition( "fecha_hasta", "mostrarCalendario_hasta"); ?>
				<select id="horas_hasta" name="horas_hasta" ><? ComboControl::Display($options_horas_hasta, $id_horas_hasta)?></select> :
				<select id="minutos_hasta" name="minutos_hasta" ><? ComboControl::Display($options_minutos_hasta, $id_minutos_hasta)?></select>
			</td>						
		</tr>		
		</table>
		
		<br>
		<br>
		<div align="center">			
			<span class="botonGrad"><input type="submit" name="btnBuscar" value="Buscar" class="boton" /></span>	
			&nbsp;&nbsp;<span class="botonGrad"><a href="?accion=ronda_adm"><input type="button" value="Limpiar" class="boton"/></a></span>			
		</div>
	</fieldset>	
	
	
</form>


<br/>
<?= $listado ?>
<br/>	