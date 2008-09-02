/*-----------------------------------------------------------
 * Generales
 ------------------------------------------------------------*/
function popup(nombre, pagina, x, y) 
{
	eval ("newwin_" + nombre + "=window.open('"+pagina+"','"+nombre+"','height='+y+',top=' + (screen.height-y)/2 + ',width='+x+',left=' + (screen.width - x)/2 + ',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no');");
	//eval ("newwin_" + nombre + ".focus();");
	return false;
}


window.onload = show;


function show(id) 
{
	var d = document.getElementById(id);
		for (var i = 1; i<=10; i++) {
			if (document.getElementById('smenu'+i)) {document.getElementById('smenu'+i).style.display='none';}
		}
	if (d) {d.style.display='block';}
}


function setActionMethod(btnName) 
{
	hidden_field = document.getElementById('js_method_action');
	hidden_field.name = btnName;
	document.forms[0].submit();
}


function escapearCaracteresEspeciales(text) 
{
  if (!arguments.callee.sRE) {
    var specials = [
      '/', '.', '*', '+', '?', '|',
      '(', ')', '[', ']', '{', '}', '\\', '\''
    ];
    arguments.callee.sRE = new RegExp(
      '(\\' + specials.join('|\\') + ')', 'g'
    );
  }
  
  return text.replace(arguments.callee.sRE, '\\$1');
}


function trimLeftRight(str) 
{
	str = str.replace(/^\s*/, '');
	str = str.replace(/\s*$/, '');
	return str;
}


function apretarBotonRecargar() 
{
	botonConf = document.getElementById("btnRecargar");	
	botonConf.click();
}


/*------------------------------------------------------------
 * Usuarios
 ------------------------------------------------------------*/

function seleccionarUsuario(id) 
{
	location.href = 'index.php?accion=usuario_mod&id=' + id;
}


function borrarUsuario(id) 
{
	var res = confirm('¿Borrar el Usuario\?')
	
	if (res)
		location.href = 'index.php?accion=usuario_del&btnProcesar=1&id=' + id;
}


/*------------------------------------------------------------
 * Planificación
 ------------------------------------------------------------*/


function borrarPlanificacion(id)
{
    apretarBotonRecargar();
	var res = confirm('¿Borrar la Planificación\?');			
	if (res) {
		if(document.getElementById('id_recoleccion')){
			id_recoleccion = document.getElementById('id_recoleccion').value;		
			location.href = 'index.php?accion=planificacion_del&id='+id+'&id_recoleccion='+id_recoleccion;
		} else {
			id_envio = document.getElementById('id_envio').value;		
			location.href = 'index.php?accion=planificacion_del&id='+id+'&id_envio='+id_envio;
		}		
	}
}


function seleccionarPlanificacion(id) 
{
	var res = confirm('¿Desea modificar la planificación\?');
	if(res) {				
		url = 'index.php?accion=planificacion_configuracion&pop=1&limpiarSession=1&id='+id;
		popup("ConfiguracionPlanificacion",url,'600','500');
	}
}


function agregarPlanificacion(id, aquien) 
{	
	var res = confirm('¿Desea agregar una planificación\?');
	if(res) {				
		if(aquien=='recoleccion'){
			url = 'index.php?accion=planificacion_configuracion&pop=1&limpiarSession=1&id_recoleccion='+id;
		} else {
			url = 'index.php?accion=planificacion_configuracion&pop=1&limpiarSession=1&id_envio='+id;
		}
		popup("ConfiguracionPlanificacion",url,'600','500');
	}
}

/*------------------------------------------------------------
 * Usuarios
 ------------------------------------------------------------*/

function seleccionarDispositivo(id) 
{
    location.href = 'index.php?accion=dispositivo_mod&id=' + id;
}


function borrarDispositivo(id) 
{
    var res = confirm('¿Borrar el Dispositivo\?')
    
    if (res)
        location.href = 'index.php?accion=dispositivo_del&btnProcesar=1&id=' + id;
}