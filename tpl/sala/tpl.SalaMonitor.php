<h2 align="right">Monitoreo de salas</h2>
<br>
<?=$errores ?>
<?=$mensaje ?>

<form method="POST" name="sala_monitor">
	<input type="hidden" name="accion" value="sala_monitor" />
			
	<?
	if($en_alarma){		
	?>
		<div align="center">
			<div style="text-align: center; width: 20%; background-color: red; border: 3px; border-style: ridge;">
				<font style="font-size: x-large; font-weight: bolder; color: white;"/>ALARMA</font><br>
			</div>		
			<span class="botonGrad"><input type="submit" name ="btnFalsaAlarma" value="Falsa alarma" class="boton" /></span><br>				
			<span class="botonGrad"><input type="submit" name ="btnAlarmaReal" value="Alarma real" class="boton" /></span><br>				
		</div>
		<bgsound src="sonidos/sonido.wav" loop="infinite"></bgsound>
		<br/>		
	<?
	}
	?>
	
	<fieldset>	
		<legend><font size="3" style="font-weight: bolder;"><?= $nombre_sala ?></font></legend>
		<br>
		
		<table width="100%" align="center" border="0">
		<tr valign="top">
			<td width="50%" align="center">
				<fieldset>
					<legend>Motas de polvo</legend>
					<br/>
					<?= $listado_motas ?>
					<br>
				</fieldset>
			</td>
			<td align="center">
				<img src="imagenes/museo/praga.jpg" title="Museo de praga" width="300px" style="border: 3px; border-style: ridge;"/>
				<br>
				<br>
				<font size="3" style="font-weight: bolder;">Guardia detectado:</font> <? if($guardia) echo $guardia; else echo "ninguno";?>
			</td>
		</tr>				
		</table>
			
		<br>
	</fieldset>
	
	<div style="display: none;">
		<span class="botonGrad"><input type="submit" id="btnRecargar" name="btnRecargar" value="Aceptar" class="boton" /></span>
	</div>	
</form>
<br/>