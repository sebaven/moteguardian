<h2 align="right"><?=PropertiesHelper::GetKey('estadisticas.alarmas')?></h2>
<br>
<?=$errores ?>
<?=$mensaje ?>

<form method="GET" name="estadistica_alarma" action="">
    <input type="hidden" name="accion" value="estadistica_alarma" />
        
        
    <fieldset>
       <legend>Estad&iacute;sticas de alarmas</legend><br>
       <br>
        
       <table width="100%" align="center" border="0">
       <tr>
			<td rowspan="2" align="right" width="25%">Fecha de b&uacute;squeda</td>
			<td align="center">
				Desde					
				<input type="text" id="fecha_desde" name="fecha_desde" readonly="readonly" size="10" value="<?= $fecha_desde ?>" />
				<button type="button" id="mostrarCalendario_desde"><img src="imagenes/calendario.png"/></button><?= getCalendarDefinition( "fecha_desde", "mostrarCalendario_desde"); ?>
				<select id="horas_desde" name="horas_desde" ><? ComboControl::Display($options_horas_desde, $id_horas_desde)?></select> :
				<select id="minutos_desde" name="minutos_desde" ><? ComboControl::Display($options_minutos_desde, $id_minutos_desde)?></select>
			</td>					
		</tr>			
		<tr>
			<td align="center">
				Hasta		
				<input type="text" id="fecha_hasta" name="fecha_hasta" readonly="readonly" size="10" value="<?= $fecha_hasta ?>" />
				<button type="button" id="mostrarCalendario_hasta"><img src="imagenes/calendario.png"/></button><?= getCalendarDefinition( "fecha_hasta", "mostrarCalendario_hasta"); ?>
				<select id="horas_hasta" name="horas_hasta" ><? ComboControl::Display($options_horas_hasta, $id_horas_hasta)?></select> :
				<select id="minutos_hasta" name="minutos_hasta" ><? ComboControl::Display($options_minutos_hasta, $id_minutos_hasta)?></select>
			</td>						
		</tr>		        
        </table>
		<br/>        
        <div align="center">            
            <span class="botonGrad"><input type="submit" name="btnBuscar" value="Buscar" class="boton" /></span>    
            &nbsp;&nbsp;<span class="botonGrad"><a href="?accion=estadistica_alarma"><input type="button" value="Limpiar" class="boton"/></a></span>
        </div>
        <br>
    </fieldset>    
    
    
</form>


<br/>
<div style="visibility: <?= $visibility ?>; text-align: center" >
    <h3><span style="color: red;"> Alarmas Reales: <?= $reales ?></span></h3>    
    <h3><span style="color: blue;"> Falsas Alarmas: <?= $falsas ?></span></h3>                           
</div>
<?= $listado; ?>
<br/>    