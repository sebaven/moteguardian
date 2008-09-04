<? if (RegistryHelper::isUserLogged()){ ?>
<div class="menu">



<!-- MENU USUARIO -->

<div class="silverheader"><a href="#">Usuario</a></div>
<div class="submenu">
    <ul>
		<? if (RegistryHelper::getRolUsuario()->id==ROL_ID_ADMINISTRADOR) {?>
		<li><a href="index.php?accion=usuario_adm">Administraci&oacute;n</a></li>
		<li><a href="index.php?accion=usuario_new">Alta</a></li>
		<? } ?>
		<li><a href="index.php?accion=cambiar_pass">Cambiar Contrase&ntilde;a</a></li>
		<li><a href="index.php?accion=logout">Salir</a></li>
	</ul>
</div>



<!--  MENU SALAS -->

<div class="silverheader"><a href="#">Salas</a></div>
<div class="submenu">
	<ul>
		<? if (RegistryHelper::getRolUsuario()->id==ROL_ID_ADMINISTRADOR) {?>
		<li><a href="index.php?accion=sala_select&redir=usuario_new&prefijoTitle=Configurar%20">Configuraci&oacute;n</a></li>
		<? } ?>
		<li><a href="index.php?accion=sala_select&redir=usuario_adm&prefijoTitle=Supervisar%20">Consulta</a></li>
	</ul>
</div>

<!--  MENU DISPOSITIVOS -->

<div class="silverheader"><a href="#">Dispositivos</a></div>
<div class="submenu">
    <ul>
        <? if (RegistryHelper::getRolUsuario()->id==ROL_ID_ADMINISTRADOR) {?>
        <li><a href="index.php?accion=dispositivo_new">Alta</a></li>
        <li><a href="index.php?accion=dispositivo_adm">Administraci&oacute;n</a></li>
        <? } ?>
    </ul>
</div>

<!--  MENU GUARDIAS -->

<div class="silverheader"><a href="#">Guardias</a></div>
<div class="submenu">
    <ul>
        <? if (RegistryHelper::getRolUsuario()->id==ROL_ID_ADMINISTRADOR) {?>
        <li><a href="index.php?accion=guardia_new">Alta</a></li>
        <li><a href="index.php?accion=guardia_adm">Administraci&oacute;n</a></li>
        <? } ?>
    </ul>
</div>
      
<!-- MENU RONDAS -->

<div class="silverheader"><a href="#">Rondas</a></div>
<div class="submenu">
    <ul>
        <? if (RegistryHelper::getRolUsuario()->id==ROL_ID_ADMINISTRADOR) {?>
        <li><a href="index.php?accion=ronda_new">Alta</a></li>
        <li><a href="index.php?accion=ronda_adm">Administraci&oacute;n</a></li>
        <? } ?>
    </ul>
</div>
           
<!--  MENU ESTADISTICAS -->

<div class="silverheader"><a href="#">Estad&iacute;sticas</a></div>
<div class="submenu">
    <ul>
        <? if (RegistryHelper::getRolUsuario()->id==ROL_ID_ADMINISTRADOR) {?>
        <li><a href="index.php?accion=estadistica_ronda_realizada">Rondas Realizadas</a></li>
        <? } ?>
    </ul>
</div>                          
         
</div>
<?}?>
