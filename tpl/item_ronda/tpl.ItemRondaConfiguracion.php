<h2 align="center"></h2>
<?=$errores ?>
<?=$mensaje ?>

<form method="POST" name="item_ronda_configuracion" id="f">
	<input type="hidden" name="accion" value="item_ronda_configuracion" />
	
	<fieldset>
		<legend>Configurar eslab&oacute;n de ronda</legend>
	
	
		<table width="70%" align="center" border="0">
		<tr>
			<td align="right">Orden</td>
			<td><input type="text" name="orden" value="<?= htmlentities($orden) ?>" style="width: 410px"/></td>			
		</tr>
		<tr>
			<td align="right">Sala</td>
			<td>
				<select name="id_sala" style="width: 415px">
	                <? ComboControl::Display($options_salas, $id_sala)?>
				</select>				            
			</td>			
		</tr>
		<tr>
			<td align="center" colspan="2">
				<br/>
				Duraci&oacute;n <select name="id_horas"><? ComboControl::Display($options_horas, $id_hora)?></select> : <select name="id_minutos"><? ComboControl::Display($options_minutos, $id_minutos)?></select>
				<br/>								            
			</td>			
		</tr>
	  	</table>
	  	
	  	<br/>
		<div align="center">
			<span class="botonGrad"><input type="submit" name="btnProcesar" value="Aceptar" class="boton"/></span>
			<span class="botonGrad"><input type="button" value="Cancelar" class="boton" onclick="window.close(); return false;"/></span>
		</div>
	</fieldset>	
	
	
</form>