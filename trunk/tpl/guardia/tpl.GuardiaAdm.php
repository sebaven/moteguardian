<h2 align="right"><?=PropertiesHelper::GetKey('administrar.guardias')?></h2>
<br>
<?=$errores ?>
<?=$mensaje ?>

<form method="GET" name="guardia_adm" action="">
	<input type="hidden" name="accion" value="guardia_adm" />
		
		
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
		<div align="center" style="color:gray;"><i>Si no completa ning&uacute;n campo trae todos los guardias</i></div>
		<br>
		<div align="center">			
			<span class="botonGrad"><input type="submit" name="btnBuscar" value="Buscar" class="boton" /></span>	
			&nbsp;&nbsp;<span class="botonGrad"><a href="?accion=guardia_adm"><input type="button" value="Limpiar" class="boton"/></a></span>			
		</div>
		<br>
	</fieldset>	
	
	
</form>


<br/>
<?= $listado ?>
<br/>	