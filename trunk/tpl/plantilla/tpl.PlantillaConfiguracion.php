<br>
<h2 align="center">Configuraci&oacute;n de plantilla</h2>
<?= $errores ?>
<?= $mensaje ?>
<form name="plantilla_configuracion" method="post">
<input type="hidden" name="accion" value="plantilla_configuracion"/>
<input type="hidden" name="id_plantilla" id="id_plantilla" value="<?= $id_plantilla ?>"/>
 
	<div style="text-align:center;">Nombre de la plantilla: <input type="text" value="<?= $nombre_plantilla?>" name="nombre_plantilla" id="nombre_plantilla" size="40"/>*<br/><br/></div>
	
	<fieldset>
	<legend><b>Agregar filtro</b></legend>
		<table border="0" align="center">		
		<tr>	
			<td align="right">Filtro:</td>
			<td><select name="tipo_filtro" id="tipo_filtro"><? ComboControl::Display($options_tipo_filtro, $id_tipo_filtro)?></select>*</td>			
		</tr>
		<tr>
			<td align="right">Nombre:</td>
			<td><input type="text" name="nombre_filtro" id="nombre_filtro" value=""/>*</td>
		</tr>
		<tr>
			<td colspan="2" align="center"><br/><input type="button" value="Agregar Filtro" class="boton" onClick="apretarBotonConfigurarFiltro();"/></td>
		</tr>				
		</table>
	</fieldset>

	<br/>
	<?= $listado ?>	
	<br/>

	<div style="text-align:center;" >
		<input type="submit" value="Guardar plantilla" class="boton" name="btnProcesar"/>
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