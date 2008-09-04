<h2 align="right"><?= $accion_guardias ?> de Guardias</h2>
<br>
<?=$errores ?>
<?=$mensaje ?>

<form method="POST" name="guardia_new">
	<input type="hidden" name="accion" value="guardia_new" />
	
	
	<fieldset>	
		<legend><?= $keys['guardia.adm.datos'] ?></legend><br>
		<br>
		
		<table width="100%" align="center" border="0">
		<tr>
			<td align="right" width="25%">Nombre</td>
			<td><input type="text" name="nombre" value="<?= htmlentities($nombre) ?>" style="width: 410px"/></td>            
		</tr>
		<tr>
			<td align="right">Codigo tarjeta RFID</td>
			<td><input type="text" name="codigo_tarjeta" value="<?= htmlentities($codigo_tarjeta) ?>" style="width: 410px"/></td>
		</tr>
		<tr>
			<td align="right">Usuario</td>
			<td>
				<select name="id_usuario" style="width: 415px">
	                <? ComboControl::Display($options_usuarios, $id_usuario)?>
	              </select>            
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
