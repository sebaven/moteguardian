<h2 align="right">Alta de rondas</h2>
<br>
<?=$errores ?>
<?=$mensaje ?>

<form method="POST" name="ronda_new">
	<input type="hidden" name="accion" value="ronda_new" />
	
	
	<fieldset>	
		<legend>Datos de la ronda</legend><br>
		<br>
		
		<table width="100%" align="center" border="0">
		<tr>
			<td align="right" width="25%">Guardia</td>
			<td>
				<select name="id_guardia" style="width: 415px">
	                <? ComboControl::Display($options_guardias, $id_guardia)?>
	              </select>            
			</td>
		</tr>					
		<tr>
			<td align="right" width="25%">Planificaciones</td>
			<td align="right">
				<img src="imagenes/new.png" title="Agregar planificaci&oacute;n..." onClick=""/> 
			</td>
		</tr>
		</table>
		
		
		<br>
			<div align="center">
				<span class="botonGrad"><input type="submit"  name ="btnProcesar" value="Aceptar" class="boton" /></span>
				<span class="botonGrad"><input type="button"  value="Cancelar" class="boton" onClick="javascript: location.href='index.php?accion=guardia_adm'; return false;" /></span>
			</div>
		<br>
	</fieldset>
	
	
</form>
<br/>
