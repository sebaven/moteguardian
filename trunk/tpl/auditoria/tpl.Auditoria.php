<div class="cuerpo">
<?= $errores ?>
<?= $mensaje ?>
<br>
<h1><?=$titulo?></h1>
	<form method="GET" name="auditoria" action="">
	<input type="hidden" name="accion" value="auditoria" />

	<div align="center">		
		<fieldset style="width: 70%">
		<legend>General</legend>
			<table>
			<tr>
				<td align="right">Desde</td>
				<td align="left">
				<input name="fecha_desde" id="fecha_desde" type="text" readonly="readonly" size="8" value="<?=$fecha_desde?>" size="10" maxlength="10" />
				<button id="calendarioDesde"><img src="imagenes/calendar-icon.gif"/></button><?= getCalendarDefinition( "fecha_desde", "calendarioDesde"); ?>
				</td>
				<td align="right">Hasta</td>
				<td align="left">
				<input name="fecha_hasta" id="fecha_hasta" type="text" readonly="readonly" size="8" value="<?=$fecha_hasta?>"
						 size="10" maxlength="10" />
				<button id="calendarioHasta"><img src="imagenes/calendar-icon.gif"/></button>
					<?= getCalendarDefinition( "fecha_hasta", "calendarioHasta"); ?>
				</td>
			</tr>
			<tr>
				<td align="right">Usuario</td>
				<td align="left">
					<select name="usuario" id="usuario"><? ComboControl::Display($options_usuarios, $usuario) ?></select>
				</td>
				<td align="right">Acci&oacute;n</td>
				<td align="left">
					<select name="acciones" id="acciones"><? ComboControl::Display($options_acciones, $acciones) ?></select>
				</td>
			</tr>
			</table>
		</fieldset>
	</div>
	
	<div align="center">
	<fieldset style="width: 70%">
	<legend>Por planilla</legend>
	<table align="center" border="0">
	<tr>
		<td><input type="text" size="2" maxlength="4" name="torneo" value="<?=$torneo?>"/></td>
		<td>&nbsp;&nbsp;-&nbsp;&nbsp;</td>
		<td><input type="text" size="2" maxlength="4" name="rueda" value="<?=$rueda?>"/></td>
		<td>&nbsp;&nbsp;-&nbsp;&nbsp;</td>
		<td><input type="text" size="2" maxlength="4" name="numero_fecha" value="<?=$numero_fecha?>"/></td>
		<td>&nbsp;&nbsp;-&nbsp;&nbsp;</td>
		<td><input type="text" size="2" maxlength="4" name="partido" value="<?=$partido?>"/></td>
	</tr>
	<tr id='codigo_planilla'>
		<td>Torneo</td>
		<td>&nbsp;</td>
		<td>Rueda</td>
		<td>&nbsp;</td>
		<td>Fecha</td>
		<td>&nbsp;</td>
		<td>Partido</td>
	</tr>
</table>
	</fieldset>
	</div>
		
	<br/>
	<div align="center">
		<input type="submit"  name="btnBuscar" value="Buscar" class="boton" />
		<input type="button"  value="Limpiar" class="boton" onClick="javascript: location.href='index.php?accion=auditoria'; return false;" />
	</div>
		<div align="center" style="color:gray;font-size:smaller;"><i>Si no completa ning&uacute;n campo trae todos los registros.</i></div>
	
</form>
<?= $listado ?>
</div>