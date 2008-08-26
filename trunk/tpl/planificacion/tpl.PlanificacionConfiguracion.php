<h2 align="center"></h2>
<?=$errores ?>
<?=$mensaje ?>
<form method="POST" name="planificacion_configuracion" id="f">
	<input type="hidden" name="accion" value="planificacion_configuracion" />
	<input type="hidden" name="id_actividad" value="<?= $id_actividad ?>" />
	<input type="hidden" name="id" value="<?= $id ?>" />
	<input type="hidden" name="id_actividad_envio" value="<?= $id_actividad_envio ?>" />
		<fieldset>
		<legend>Configurar planificaci&oacute;n</legend>
		<table width="70%" align="center" border="0">
			<tr>
				<td align="left">
					<input type="radio" <?= $semanalmente_checked ?> onclick="habilitarSemanalmente();" id="semanalmente" name="radioFrecuencia" value="semanalmente"/>Semanalmente
				</td>
				<td>
					<select name="dia" id="dia" ><? ComboControl::Display($options_dia, $id_dia)?></select>
				</td>
				<td align="right">
					<select name="hora_semanalmente" id="hora_semanalmente" ><? ComboControl::Display($options_hora_semanalmente, $id_hora_semanalmente)?></select> :
					<select name="minutos_semanalmente" id="minutos_semanalmente" ><? ComboControl::Display($options_minutos_semanalmente, $id_minutos_semanalmente)?></select>
				</td>
			</tr>
			<tr>
				<td align="left">
					<input type="radio"  <?= $diariamente_checked ?> onclick="habilitarDiariamente();" name="radioFrecuencia" value="diariamente" id="diariamente"/>Diariamente
				</td>
				<td colspan="2" align="right">
					<select name="hora_diariamente" id="hora_diariamente"><? ComboControl::Display($options_hora_diariamente, $id_hora_diariamente)?></select> :
					<select name="minutos_diariamente" id="minutos_diariamente" ><? ComboControl::Display($options_minutos_diariamente, $id_minutos_diariamente)?></select>
				</td>
			</tr>
			<tr>
				<td align="left">
					<input type="radio"  <?= $absoluto_checked ?> name="radioFrecuencia" onclick="habilitarAbsoluto();" value="absoluto" id="absoluto"/>Absoluto
				</td>
				<td>
					<input type="text" id="fecha" name="fecha" readonly="readonly" size="10" value="<?= $fecha?>" />
					<button type="button" id="mostrarCalendario">...</button><?= getCalendarDefinition( "fecha", "mostrarCalendario"); ?>
				</td>
				<td align="right">					
					<select id="hora_absoluto" name="hora_absoluto" ><? ComboControl::Display($options_hora_absoluto, $id_hora_absoluto)?></select> :
					<select id="minutos_absoluto" name="minutos_absoluto" ><? ComboControl::Display($options_minutos_absoluto, $id_minutos_absoluto)?></select>
				</td>
			</tr>
				
  		</table>
  		<br/>
		<div align="center">
			<input type="submit" name="btnProcesar" value="Aceptar" class="boton"/>
			<input type="button" value="Cancelar" class="boton" onclick="window.close(); return false;"/>
		</div>
		</fieldset>	
</form>
<script type="text/javascript">
if(!document.getElementById('semanalmente').checked){
	document.getElementById('dia').disabled = true;
	document.getElementById('hora_semanalmente').disabled = true;		
	document.getElementById('minutos_semanalmente').disabled = true;
}		
if(!document.getElementById('diariamente').checked){
	document.getElementById('hora_diariamente').disabled = true;		
	document.getElementById('minutos_diariamente').disabled = true;
}		
if(!document.getElementById('absoluto').checked){
	document.getElementById('fecha').disabled = true;		
	document.getElementById('hora_absoluto').disabled = true;		
	document.getElementById('minutos_absoluto').disabled = true;
	document.getElementById('mostrarCalendario').disabled = true;
}	
function habilitarSemanalmente(){
	document.getElementById('dia').disabled = false;
	document.getElementById('hora_semanalmente').disabled = false;		
	document.getElementById('minutos_semanalmente').disabled = false;		
	document.getElementById('hora_diariamente').disabled = true;		
	document.getElementById('minutos_diariamente').disabled = true;		
	document.getElementById('fecha').disabled = true;		
	document.getElementById('hora_absoluto').disabled = true;		
	document.getElementById('minutos_absoluto').disabled = true;
	document.getElementById('mostrarCalendario').disabled = true;
}
function habilitarDiariamente(){
	document.getElementById('dia').disabled = true;
	document.getElementById('hora_semanalmente').disabled = true;		
	document.getElementById('minutos_semanalmente').disabled = true;		
	document.getElementById('hora_diariamente').disabled = false;		
	document.getElementById('minutos_diariamente').disabled = false;		
	document.getElementById('fecha').disabled = true;		
	document.getElementById('hora_absoluto').disabled = true;		
	document.getElementById('minutos_absoluto').disabled = true;
	document.getElementById('mostrarCalendario').disabled = true;
}
function habilitarAbsoluto(){
	document.getElementById('dia').disabled = true;
	document.getElementById('hora_semanalmente').disabled = true;		
	document.getElementById('minutos_semanalmente').disabled = true;		
	document.getElementById('hora_diariamente').disabled = true;		
	document.getElementById('minutos_diariamente').disabled = true;		
	document.getElementById('fecha').disabled = false;		
	document.getElementById('hora_absoluto').disabled = false;		
	document.getElementById('minutos_absoluto').disabled = false;
	document.getElementById('mostrarCalendario').disabled = false;
}
</script>