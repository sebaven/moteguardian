<h2 align="right">Alta de rondas</h2>
<br>
<?=$errores ?>
<?=$mensaje ?>

<form method="POST" name="ronda_new">
	<input type="hidden" name="accion" value="ronda_new" />
	<input type="hidden" name="id_ronda" id="id_ronda" value="<?= $id_ronda ?>"/>
		
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
		<tr>
			<td align="center" colspan="2">
				<br>
				<fieldset>
					<legend>Planificaciones <img src="imagenes/new.png" title="Agregar planificaci&oacute;n..." onclick="agregarPlanificacion(<?= $id_ronda ?>);" style="cursor: pointer"/></legend>
					<br>	
					<?= $listado_planificaciones?>
					<br>
				</fieldset>
							
				 
			</td>
		</tr>
		<tr>
			<td align="center" colspan="2">
				<br>
				<fieldset>
					<legend>Recorrido <img src="imagenes/new.png" title="Agregar eslab&oacute;n..." onclick="agregarItemRonda(<?= $id_ronda ?>);" style="cursor: pointer"/></legend>
					<br>
					<?= $listado_eslabones?>
					<br>
				</fieldset>
			</td>
		</tr>		
		</table>
		
		
		<br>
		<br>
		<br>
		<div align="center">
			<span class="botonGrad"><input type="submit" name ="btnProcesar" value="Finalizar" class="boton" /></span>			
		</div>
		<br>
	</fieldset>
	
	<div style="display: none;">
		<span class="botonGrad"><input type="submit" id="btnRecargar" name="btnRecargar" value="Aceptar" class="boton" /></span>
	</div>	
</form>
<br/>

