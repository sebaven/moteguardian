<h2 align="right"><?=PropertiesHelper::GetKey('administrar.dispositivos')?></h2>
<br>
<?=$errores ?>
<?=$mensaje ?>

<form method="GET" name="dispositivo_adm" action="">
	<input type="hidden" name="accion" value="dispositivo_adm" />
		
		
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
		<div align="center" style="color:gray;"><i>Si no completa ning&uacute;n campo trae todos los dispositivos</i></div>
		<br>
		<div align="center">			
			<span class="botonGrad"><input type="submit" name="btnBuscar" value="Buscar" class="boton" /></span>	
			&nbsp;&nbsp;<span class="botonGrad"><a href="?accion=dispositivo_adm"><input type="button" value="Limpiar" class="boton"/></a></span>			
		</div>
		<br>
	</fieldset>	
	
	
</form>


<br/>
<?= $listado ?>
<br/>	