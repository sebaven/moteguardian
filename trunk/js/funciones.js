/*-----------------------------------------------------------
 * Generales
 ------------------------------------------------------------*/
function popup(nombre, pagina, x, y) 
{
	eval("newwin_" + nombre + "=window.open('" + pagina + "','" + nombre + "','height='+y+',top=' + (screen.height-y)/2 + ',width='+x+',left=' + (screen.width - x)/2 + ',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no');");
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
	var res = confirm('¿Desea borrar la Planificación\?');			
	if (res) {
		location.href = 'index.php?accion=planificacion_del&id='+id+'&id_ronda='+document.getElementById('id_ronda').value;
	} 
}


function seleccionarPlanificacion(id) 
{
	var res = confirm('¿Desea modificar la planificación\?');
	if(res) {				
		url = 'index.php?accion=planificacion_configuracion&pop=1&limpiarSession=1&id='+id;
		popup("ConfiguracionPlanificacion",url,'600','250');
	}
}


function agregarPlanificacion(id) 
{	
	url='index.php?pop=1&limpiarSession=1&accion=planificacion_configuracion&id_ronda='+id;		
	popup("ConfiguracionPlanificacion",url,'600','250');	
}


/*------------------------------------------------------------
 * Item ronda
 ------------------------------------------------------------*/


function borrarItemRonda(id)
{    
	apretarBotonRecargar();
	var res = confirm('¿Desea borrar el eslabón de la ronda\?');			
	if (res) {
		location.href = 'index.php?accion=item_ronda_del&id='+id+'&id_ronda='+document.getElementById('id_ronda').value;
	} 
}


function seleccionarItemRonda(id) 
{
	var res = confirm('¿Desea modificar el eslabón de la ronda\?');
	if(res) {				
		url = 'index.php?accion=item_ronda_configuracion&pop=1&limpiarSession=1&id='+id;
		popup("ConfiguracionItemRonda",url,'600','250');
	}
}


function agregarItemRonda(id) 
{	
	url='index.php?pop=1&limpiarSession=1&accion=item_ronda_configuracion&id_ronda='+id;		
	popup("ConfiguracionItemRonda",url,'600','250');	
}


/*------------------------------------------------------------
 * Rondas
 ------------------------------------------------------------*/

function seleccionarRonda(id) 
{
    location.href = 'index.php?accion=ronda_new&id_ronda=' + id;
}


function borrarRonda(id) 
{
    var res = confirm('¿Borrar el Ronda\?')
    
    if (res)
        location.href = 'index.php?accion=ronda_del&btnProcesar=1&id=' + id;
}

/*------------------------------------------------------------
 * Dispositivos
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

/*------------------------------------------------------------
 * Guardias
 ------------------------------------------------------------*/

function seleccionarGuardia(id) 
{
    location.href = 'index.php?accion=guardia_mod&id=' + id;
}


function borrarGuardia(id) 
{
    var res = confirm('¿Borrar el Guardia\?')
    
    if (res)
        location.href = 'index.php?accion=guardia_del&btnProcesar=1&id=' + id;
}