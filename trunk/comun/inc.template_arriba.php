<?
	ob_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="es">
<head>
	<style type="text/css">@import url(lib/calendario/calendar-blue.css);</style>
	<script type="text/javascript" src="lib/calendario/calendar.js"></script>
	<script type="text/javascript" src="lib/calendario/lang/calendar-es.js"></script>
	<script type="text/javascript" src="lib/calendario/calendar-setup.js"></script>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>SRDF</title>
	<link href="css/estilos.css" rel="stylesheet" type="text/css">
	<link href="css/estilos_listado.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="js/funciones.js"></script>
	<!--[if IE]>
	<style type="text/css" media="screen">
		#menu{float:none;} /* This is required for IE to avoid positioning bug when placing content first in source. */
		/* IE Menu CSS */
		/* csshover.htc file version: V1.21.041022 - Available for download from: http://www.xs4all.nl/~peterned/csshover.html */
		body{behavior:url(css/csshover.htc);
		font-size:100%; /* to enable text resizing in IE */
		}
		#menu ul li{float:left;width:100%;}
		#menu h2, #menu a{height:1%;}
	</style>
	<![endif]-->
</head>
<body>
	<div class="app">
		<div class="encabezado">
			<div style="float:left;">
				<?  if (RegistryHelper::isUserLogged())
					{
				?>
						Usuario: <b><?= RegistryHelper::getUsername() ?></b>
				<?
					}
				?>
			</div>
			<div style="float:right;">
				<?= getFecha(date('Y/m/d w')); ?>
			</div>
		</div>
		<div class="tabla_encabezado" onClick="javascript: location.href='index.php?accion=inicio'; return false;">
			<span style="font-weight:bold; font-size:20px; font-family:Verdana;"><img alt="SRDF" src="imagenes/srdfip.png"/></span>
		</div>
		<?if ($_GET["accion"]!= "login") { ?>
			<?include_once "comun/inc.menu.php"?>
		<?}?>