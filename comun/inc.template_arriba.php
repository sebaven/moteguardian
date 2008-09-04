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
	<script type="text/javascript" src="js/jquery-1.2.2.pack.js"></script>
	<script type="text/javascript" src="js/ddaccordion.js"></script>
	<script type="text/javascript" src="js/funciones.js"></script>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>MoteGuardian</title>
	<link href="css/estilos.css" rel="stylesheet" type="text/css">
	<link href="css/estilos_listado.css" rel="stylesheet" type="text/css">
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
	<script type="text/javascript">
		ddaccordion.init({
			headerclass: "silverheader", //Shared CSS class name of headers group
			contentclass: "submenu", //Shared CSS class name of contents group
			revealtype: "mouseclick", //Reveal content when user clicks or onmouseover the header? Valid value: "click" or "mouseover
			collapseprev: true, //Collapse previous content (so only one open at any time)? true/false
			defaultexpanded: [0], //index of content(s) open by default [index1, index2, etc] [] denotes no content
			onemustopen: true, //Specify whether at least one header should be open always (so never all headers closed)
			animatedefault: false, //Should contents open by default be animated into view?
			persiststate: true, //persist state of opened contents within browser session?
			toggleclass: ["", "selected"], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
			togglehtml: ["", "", ""], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
			animatespeed: "normal", //speed of animation: "fast", "normal", or "slow"
			oninit:function(headers, expandedindices){ //custom code to run when headers have initalized
				//do nothing
			},
			onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
				//do nothing
			}
		})	
	</script>
</head>
<body>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="top"><table width="950" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="left" valign="top"><table width="950" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="950" height="25">
            <table width="950" border="0" cellspacing="0" cellpadding="0">
              <tr height="25">
                <td width="20" height="25" background="imagenes/bg_gradient25x1blue.jpg">&nbsp;</td>
                <td width="630" height="25" background="imagenes/bg_gradient25x1blue.jpg" class="Estilo2">
                	<?  if (RegistryHelper::isUserLogged())
						{
					?>
							Usuario: <b><?= RegistryHelper::getUsername() ?></b>
					<?
						}
					?>
                </td>
                <td width="300" height="25" align="center" background="imagenes/bg_gradient25x1silver.jpg" class="Estilo2">
                	<span class="Estilo2" id="servertime" style="width: auto;" ></span>
                </td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td height="100" align="left" valign="top" background="imagenes/header.jpg"><table width="950" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td height="24" align="center">&nbsp;</td>
                <td width="24" height="24" align="center"></td>
                <td width="24" height="24" align="center"></td>
                <td width="24" height="24" align="center">
                	<?  if (RegistryHelper::isUserLogged())
						{
					?>
                	<a href="index.php?accion=logout">
                		<img src="imagenes/1b.gif" style="border: none;" alt="" width="22" height="22" />
                	</a>
                	<?
						}
					?>
                </td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align="left" valign="top"><table width="950" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="150" align="left" valign="top" background="imagenes/bg_gradient1x150menu.jpg">
				<?if ($_GET["accion"]!= "login") { ?>
					<?include_once "comun/inc.menu.php"?>
				<?}?>
			</td>
			<td width="750" align="left" valign="top" bgcolor="#FFFFFF">
	            <table width="780" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	              <tr>
	                <td id="contenido">