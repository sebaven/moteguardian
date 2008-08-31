<h2 align="right">Configuraci&oacute;n de plantilla</h2>
<br>
<?= $errores ?>
<?= $mensaje ?>
<form name="plantilla_configuracion" method="post">
<input type="hidden" name="accion" value="plantilla_configuracion"/>
<input type="hidden" name="id_plantilla" id="id_plantilla" value="<?= $id_plantilla ?>"/>
 
	<p align="center">* Plantilla: <input type="text" value="<?= htmlentities($nombre_plantilla) ?>" name="nombre_plantilla" id="nombre_plantilla" size="40"/><br/><br/></p>
	
	<fieldset>
	<legend><b>Agregar filtro</b></legend>
		<table border="0" align="center">		
		<tr>	
			<td align="right">* Tipo de Filtro: </td>
			<td><select name="tipo_filtro" id="tipo_filtro"><? ComboControl::Display($options_tipo_filtro, $id_tipo_filtro)?></select></td>			
		</tr>
		<tr>
			<td align="right">* Filtro: </td>
			<td><input type="text" name="nombre_filtro" id="nombre_filtro" value=""/></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><br/><span class="botonGrad"><input type="button" value="Agregar Filtro" class="boton" onClick="apretarBotonConfigurarFiltro();"/></span></td>
		</tr>				
		</table>
	</fieldset>

	<br/>
	<?= $listado ?>	
	<br/>

	<div style="text-align:center;" >
		<span class="botonGrad"><input type="submit" value="Guardar plantilla" class="boton" name="btnProcesar"/></span>
	</div>
	<div style="display:none">
		<input type="submit" name="btnRecargar" value="Recargar" id="btnRecargar"/>
		<input type="submit" name="btnConfigurarFiltro" value="Configurar Filtro" id="btnConfigurarFiltro"/>		 
	</div>
</form>
<script type="text/javascript">
	var idNodoPlantilla = "<?= $id_nodo_plantilla ?>";
	var accFiltro = "<?= $acc_filtro ?>";
	if(accFiltro != " ") {		
		if (idNodoPlantilla!=""){				
			url = "index.php?accion=filtro_<?= $acc_filtro ?>&pop=1&id_nodo_plantilla=<?= $id_nodo_plantilla ?>&limpiarSession=1";		
			popup("ConfiguracionFiltro",url,'500','500'); 
		}
	} else {
		apretarBotonRecargar();		
	}
	
</script>