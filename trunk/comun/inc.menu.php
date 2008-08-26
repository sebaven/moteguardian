<? if (RegistryHelper::isUserLogged()){ ?>
<div id="menu">
<ul>
  <li><h2>Usuario</h2>
    <ul>
      <? if (RegistryHelper::getRolUsuario()->id==ROL_ID_ADMIN) {?>
      <li><a href="index.php?accion=usuario_adm">Administraci&oacute;n</a></li>
      <li><a href="index.php?accion=usuario_new">Alta</a></li>
      <? } ?>
      <li><a href="index.php?accion=cambiar_pass">Cambiar Contrase&ntilde;a</a></li>
    </ul>
  </li>
</ul>
<? if (RegistryHelper::getRolUsuario()->id==ROL_ID_ADMIN) {?>
<ul>
  <li><h2>Centrales</h2>
    <ul>
      <li><a href="index.php?accion=central_adm">Consulta y Administraci&oacute;n</a></li>
      <li><a href="index.php?accion=central_new">Alta</a></li>
    </ul>
</ul>
<? } ?>
<? if (RegistryHelper::getRolUsuario()->id==ROL_ID_ADMIN) {?>
<ul>
  <li><h2>Plantillas</h2>
    <ul>
      <li><a href="index.php?accion=plantilla_admin">Consulta y Administraci&oacute;n</a></li>
      <li><a href="index.php?accion=plantilla_configuracion">Alta</a></li>      
    </ul>
</ul>
<? } ?>

<ul>
  <li><h2>Actividades</h2>
    <ul>
      <? if (RegistryHelper::getRolUsuario()->id==ROL_ID_ADMIN) {?>
      <li><a href="index.php?accion=actividad_bus">Consulta y Administraci&oacute;n</a></li>
      <li><a href="index.php?accion=actividad_new">Alta</a></li>  
      <? } ?>
      <li><a href="index.php?accion=monitoreo_tareas">Monitoreo y Control de Tareas y Actividades</a></li>          
    </ul>
</ul>

<ul>
  <li><h2>Sistema</h2>
    <ul>
      <li><a href="index.php?accion=logout">Salir</a></li>
	  <? if (RegistryHelper::getRolUsuario()->id==ROL_ID_ADMIN) {?>
      <li><a href="index.php?accion=configuracion_sistema">Configuraci&oacute;n del sistema</a></li>
      <?} ?>
    </ul>
</ul>
</div>
<?}?>