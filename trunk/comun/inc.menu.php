<? if (RegistryHelper::isUserLogged()){ ?>
<div class="menu">
<div class="silverheader"><a href="#" >Usuario</a></div>
	<div class="submenu">
    <ul>
      <? if (RegistryHelper::getRolUsuario()->id==ROL_ID_ADMIN) {?>
      <li><a href="index.php?accion=usuario_adm">Administraci&oacute;n</a></li>
      <li><a href="index.php?accion=usuario_new">Alta</a></li>
      <? } ?>
      <li><a href="index.php?accion=cambiar_pass">Cambiar Contrase&ntilde;a</a></li>
    </ul>
</div>
<? if (RegistryHelper::getRolUsuario()->id==ROL_ID_ADMIN) {?>
<div class="silverheader"><a href="#" >Centrales</a></div>
	<div class="submenu">
    <ul>
      <li><a href="index.php?accion=central_adm">Consulta y Administraci&oacute;n</a></li>
      <li><a href="index.php?accion=central_new">Alta</a></li>
      <li><a href="index.php?accion=tecnologia_central_admin">Administraci&oacute;n de tecnolog&iacute;as</a></li>    
    </ul>
</div>    
<? } ?>
<? if (RegistryHelper::getRolUsuario()->id==ROL_ID_ADMIN) {?>
<div class="silverheader"><a href="#" >Plantillas</a></div>
	<div class="submenu">
    <ul>
      <li><a href="index.php?accion=plantilla_admin">Consulta y Administraci&oacute;n</a></li>
      <li><a href="index.php?accion=plantilla_configuracion">Alta</a></li>      
    </ul>
</div>
<div class="silverheader"><a href="#" >Recolecciones</a></div>
	<div class="submenu">
	<ul>
		<li><a href="index.php?accion=recoleccion_admin">Consulta y administraci&oacute;n</a></li>
		<li><a href="index.php?accion=recoleccion_conf">Alta</a></li>
		<li><a href="index.php?accion=recoleccion_manual_conf">Recolecci&oacute;n Manual</a></li>
	</ul>
</div>
<? } ?>
<div class="silverheader"><a href="#" >Actividades</a></div>
	<div class="submenu">
    <ul>
      <? if (RegistryHelper::getRolUsuario()->id==ROL_ID_ADMIN) {?>
      <li><a href="index.php?accion=actividad_bus">Consulta y Administraci&oacute;n</a></li>
      <li><a href="index.php?accion=actividad_new">Alta</a></li>  
      <li><a href="index.php?accion=visualizacion_circuitos">Visualizaci&oacute;n de circuitos</a></li>
      <li><a href="index.php?accion=recolecciones_y_envios_en_curso">Administraci&oacute;n de recolecciones y env&iacute;os en curso</a></li>
      <? } ?>
      <li><a href="index.php?accion=monitoreo_tareas">Monitoreo y Control de Tareas</a></li>                
    </ul>
</div>
<? if (RegistryHelper::getRolUsuario()->id==ROL_ID_ADMIN) {?>
<div class="silverheader"><a href="#" >Hosts</a></div>
	<div class="submenu">
    <ul>
      <li><a href="index.php?accion=host_adm">Consulta y Administraci&oacute;n</a></li>
      <li><a href="index.php?accion=host_new">Alta</a></li>
    </ul>
</div>
<? } ?>
<? if (RegistryHelper::getRolUsuario()->id==ROL_ID_ADMIN) {?>
<div class="silverheader"><a href="#" >Env&iacute;os</a></div>
	<div class="submenu">
    <ul>
      <li><a href="index.php?accion=envio_bus">Consulta y Administraci&oacute;n</a></li>
      <li><a href="index.php?accion=envio_adm">Alta</a></li>
      <li><a href="index.php?accion=envio_manual_conf">Env&iacute;o Manual</a></li>
    </ul>
</div>
<? } ?>
<? if (RegistryHelper::getRolUsuario()->id==ROL_ID_ADMIN) {?>
<div class="silverheader"><a href="#" >Monitoreo en tiempo real</a></div>
<div class="submenu">
    <ul>
      <li><a href="index.php?accion=monitoreo_tiempo_real">Monitoreo en tiempo real</a></li>
    </ul>
</div>
<? } ?>
<? if (RegistryHelper::getRolUsuario()->id==ROL_ID_ADMIN) {?>
<div class="silverheader"><a href="#" >Estad&iacute;sticas</a></div>
<div class="submenu">
    <ul>
      <li><a href="index.php?accion=est_intentos_recoleccion_y_envios">Intentos de Recolecci&oacute;n y Env&iacute;os</a></li>
      <li><a href="index.php?accion=est_total_tickets_y_ficheros">Total de Tickets y Ficheros</a></li>
    </ul>
</div>
<? } ?>
<div class="silverheader"><a href="#" >Sistema</a></div>
<div class="submenu">
    <ul>
      <li><a href="index.php?accion=logout">Salir</a></li>
	  <? if (RegistryHelper::getRolUsuario()->id==ROL_ID_ADMIN) {?>
      <li><a href="index.php?accion=configuracion_sistema">Configuraci&oacute;n del sistema</a></li>
      <?} ?>
    </ul>
</div>

</div>
<?}?>