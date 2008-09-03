<h2 align="right"><?= $accion_dispositivos ?> de Dispositivos</h2>
<br>
<?=$errores ?>
<?=$mensaje ?>

<form method="POST" name="dispositivo_new">
	<input type="hidden" name="accion" value="dispositivo_new" />
	
	
	<fieldset>	
		<legend><?= $keys['dispositivo.adm.datos'] ?></legend><br>
		<br>
		
		<table width="100%" align="center" border="0">
		<tr>
			<td align="right" width="25%">C&oacute;digo</td>
			<td><input type="text" name="codigo" value="<?= htmlentities($codigo) ?>" style="width: 410px"/></td>            
		</tr>
		<tr>
			<td align="right">Tipo</td>
			<td>
				<select name="tipo" style="width: 415px">
					<? ComboControl::Display($options_tipo, $tipo)?>
				</select>            
			</td>
		</tr>
		<tr>
			<td align="right">Sala</td>
			<td>
				<select name="id_sala" style="width: 415px">
	                <? ComboControl::Display($options_sala, $id_sala)?>
	              </select>            
			</td>			
		</tr>
		<tr>            
	            <td align="right">Estado</td>
	            <td>
	            <select name="estado" style="width: 415px">
					<? ComboControl::Display($options_estado, $estado)?>
				</select>
	            </td>
		</tr>		
		<tr valign="top">
			<td align="right">
	          	Descripci&oacute;n
	          </td>
	          <td>
	          	<textarea rows="3" cols="49" name="descripcion" style="width: 410px"><?= htmlentities($descripcion) ?></textarea>
	          </td>
		</tr>		
		</table>
		
		
		<br>
			<div align="center">
				<span class="botonGrad"><input type="submit"  name ="btnProcesar" value="Aceptar" class="boton" /></span>
				<span class="botonGrad"><input type="button"  value="Cancelar" class="boton" onClick="javascript: location.href='index.php?accion=usuario_adm'; return false;" /></span>
			</div>
		<br>
	</fieldset>
	
	
</form>
<br/>
