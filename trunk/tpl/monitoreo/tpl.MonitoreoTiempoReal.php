<h2 align="right"><?=PropertiesHelper::GetKey('monitoreo.tiempo.real')?></h2>
<br>
<?=$errores ?>
<?=$mensaje ?>

<form method="GET" name="monitoreo_tiempo_real" action="">
	<input type="hidden" name="accion" value="monitoreo_tiempo_real" />
</form>
<br/>
<?= $listadoRecolecciones?>
<?= $listadoEnvios?>