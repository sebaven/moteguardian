<?
include_once(BASE_DIR."comun/Fecha.php");
$fecha = new Fecha();
$fecha->loadFromNow();
?>
			<div class="pie">Versi&oacute;n Beta - <?=$fecha->toString();?></div>
		</div>
	</body>
</html>
<?
	ob_end_flush();
?>