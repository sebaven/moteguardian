<? 
if( RegistryHelper::isUserLogged() ){ 
?>
<div id="menu">
<ul>
	<li><h2>Usuario</h2>
		<ul>
			<? if( RegistryHelper::getRolUsuario()->id==ROL_ID_ADMIN ){	?>
				<li><a href="index.php?accion=usuario_adm">Administraci&oacute;n</a></li>
				<li><a href="index.php?accion=usuario_new">Alta</a></li>
			<? } ?>
      		<li><a href="index.php?accion=cambiar_pass">Cambiar Contrase&ntilde;a</a></li>
    	</ul>
  	</li>
</ul>
<ul>
	<li><h2>Salas</h2>		
    	<ul>
			<? if( RegistryHelper::getRolUsuario()->id==ROL_ID_ADMIN ){	?>
				<li><a href="index.php?accion=sala_select&redir=usuario_new&prefijoTitle=Configurar%20">Configuraci&oacute;n</a></li>			
			<? } ?>
      		<li><a href="index.php?accion=sala_select&redir=usuario_adm&prefijoTitle=Supervisar%20">Consulta</a></li>
		</ul>
	</li>
</ul>
<ul>
	<li><h2>Sistema</h2>
    	<ul>
      		<li><a href="index.php?accion=logout">Salir</a></li>
		</ul>
	</li>
</ul>
</div>
<?
}
?>