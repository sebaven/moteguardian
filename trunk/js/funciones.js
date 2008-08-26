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
 
/*-----------------------------------------------------------
 * Generales
 ------------------------------------------------------------*/
function popup(nombre, pagina, x, y){
	eval ("newwin_" + nombre + "=window.open('"+pagina+"','"+nombre+"','height='+y+',top=' + (screen.height-y)/2 + ',width='+x+',left=' + (screen.width - x)/2 + ',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no');");
	eval ("newwin_" + nombre + ".focus();");
	return false;
}

window.onload=show;

function show(id) {
	var d = document.getElementById(id);
		for (var i = 1; i<=10; i++) {
			if (document.getElementById('smenu'+i)) {document.getElementById('smenu'+i).style.display='none';}
		}
	if (d) {d.style.display='block';}
}

function setActionMethod(btnName) {
	hidden_field = document.getElementById('js_method_action');
	hidden_field.name = btnName;
	document.forms[0].submit();
}

function escapearCaracteresEspeciales(text) {
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

function trimLeftRight(str) {
	str = str.replace(/^\s*/, '');
	str = str.replace(/\s*$/, '');
	return str;
}

/*-----------------------------------------------------------
 * Plantilla
 ------------------------------------------------------------*/

function seleccionarPlantilla(id)
{
	location.href = 'index.php?accion=plantilla_configuracion&id_plantilla=' + id;
}
 
function apretarBotonConfigurarFiltro() {
	// Valido que se haya seleccionado un tipo de filtro
	obj_tipo_filtro = document.getElementById("tipo_filtro");	
	id_tipo_filtro = obj_tipo_filtro.options[obj_tipo_filtro.selectedIndex].value;
	if(id_tipo_filtro==0){
		window.alert('Debe seleccionar un tipo de filtro');
		return;
	}
	// Valido que se haya establecido un nombre para el filtro
	obj_nombre_filtro = document.getElementById("nombre_filtro");
	var nombre_validar = trimLeftRight(obj_nombre_filtro.value);
	if(nombre_validar == "") {
		window.alert('Debe establecer un nombre para el filtro');
		return;
	}
	// Hago submit para que se ejecute la función ConfigurarFiltro en la acción
	botonConf = document.getElementById("btnConfigurarFiltro");
	botonConf.click();	
}

function apretarBotonRecargar(){
	botonConf = document.getElementById("btnRecargar");	
	botonConf.click();
}

function borrarNodoPlantilla(id){
	var res = confirm('¿Borrar el Filtro\?')	
	if (res){
		obj_tipo_filtro = document.getElementById("tipo_filtro");	
		id_tipo_filtro = obj_tipo_filtro.options[obj_tipo_filtro.selectedIndex].value;	 
		nombre_plantilla = document.getElementById('nombre_plantilla').value;
		idp = document.getElementById('id_plantilla').value;
		idnp = id.split('-',1);
		location.href = 'index.php?accion=borrar_filtro&id='+idnp+'&id_plantilla='+idp+'&id_tipo_filtro='+id_tipo_filtro+'&nombre_plantilla='+nombre_plantilla;
	}
}

function borrarPlantilla(id) {
	var res = confirm('¿Borrar la plantilla\?')

	if (res)
		location.href = 'index.php?accion=plantilla_del&id=' + id;
}

function salirConfiguracionFiltro(id){
	obj_tipo_filtro = document.getElementById("tipo_filtro");	
	id_tipo_filtro = obj_tipo_filtro.options[obj_tipo_filtro.selectedIndex].value;	 
	nombre_plantilla = document.getElementById('nombre_plantilla').value;
	location.href = 'index.php?accion=borrar_filtro&id='+id+'&id_plantilla='+idp+'&id_tipo_filtro='+id_tipo_filtro+'&nombre_plantilla='+nombre_plantilla;	
}

function seleccionarFiltro(id){
	parametros = id.split('-',2);
	idnp = parametros[0];
	accion = parametros[1];	
	obj_tipo_filtro = document.getElementById("tipo_filtro");	
	id_tipo_filtro = obj_tipo_filtro.options[obj_tipo_filtro.selectedIndex].value;	
	nombre_tipo_filtro = obj_tipo_filtro.options[obj_tipo_filtro.selectedIndex].text;	
	id_plantilla = document.getElementById('id_plantilla').value;			
	url = "index.php?accion=filtro_"+ accion +"&pop=1&id_tipo_filtro=" + id_tipo_filtro + "&nombre_tipo_filtro=" + nombre_tipo_filtro + "&id_plantilla=" + id_plantilla + "&limpiarSession=1&id_nodo_plantilla="+idnp;	
	if(accion!=" ")popup("ConfiguracionFiltro",url,'500','500');
}

/*-----------------------------------------------------------
 * Central
 ------------------------------------------------------------*/

function configurarRecolector(){
	// Se obtienen los datos de la tecnologia del recolector
	obj_tecnologia_recolector = document.getElementById("id_tecnologia_recolector");
	id_tecnologia_recolector = obj_tecnologia_recolector.options[obj_tecnologia_recolector.selectedIndex].value;
	valorTecnologia = obj_tecnologia_recolector.options[obj_tecnologia_recolector.selectedIndex].text;
	
	// Se obtienen los datos de la tecnologia de la central
	obj_tecnologia_central = document.getElementById("id_tecnologia_central");
	id_tecnologia_central = obj_tecnologia_central.options[obj_tecnologia_central.selectedIndex].value;
	valorTecnologiaCentral = obj_tecnologia_central.options[obj_tecnologia_central.selectedIndex].text;

	nombreCentral = escape(document.getElementById("nombre").value);
	codigoCentral = escape(document.getElementById("codigo").value);
	descripcionCentral = escape(document.getElementById("descripcion").value);
		
	nombreAccion = document.getElementById("accion_tecnologia_recolector").value;
	idCentral = document.getElementById("id_central").value;
	url = "index.php?accion=" + nombreAccion + "&pop=1&id_tecnologia_recolector=" + id_tecnologia_recolector + "&tecnologia=" + valorTecnologia + "&nombreCentral=" + nombreCentral;
	url = url + "&codigoCentral=" + codigoCentral + "&idCentral=" + idCentral + "&id_tecnologia_central=" + id_tecnologia_central + "&descripcionCentral=" + descripcionCentral;
	popup("ConfiguracionRecolector",url,'700','500');
	return false;
}

function seleccionarCentral(id)
{
	location.href = 'index.php?accion=central_mod&id=' + id;
}


function borrarCentral(id)
{
	var res = confirm('¿Borrar la Central\?')	
	if (res)
		location.href = 'index.php?accion=central_del&id='+id;
}

function setEliminarOrigen(idOrigen) {
	hidden_field = document.getElementById('js_method_action');
	hidden_field.name = "btnEliminarOrigenFTP";
	hidden_id_fila = document.getElementById('id_fila_borrar');
	hidden_id_fila.value = idOrigen;
	document.forms[0].submit();
}

function setEditarOrigen(idOrigen) {
	hidden_field = document.getElementById('js_method_action');
	hidden_field.name = "btnEditarOrigenFTP";
	hidden_id_fila = document.getElementById('id_fila_borrar');
	hidden_id_fila.value = idOrigen;
	document.forms[0].submit();
}

/*-----------------------------------------------------------
 * Actividad
 ------------------------------------------------------------*/
function seleccionarActividad(id)
{   
	location.href = 'index.php?accion=actividad_adm&id=' + id;
}

function borrarPlanificacion(id){
    apretarBotonRecargar();
	var res = confirm('¿Borrar la Planificación\?');			
	if (res) {
		id_actividad = document.getElementById('id_actividad').value;		
		location.href = 'index.php?accion=planificacion_del&id='+id+'&id_actividad='+id_actividad;
	}
}

function seleccionarPlanificacion(id) {
	var res = confirm('¿Desea modificar la planificación\?');
	if(res) {				
		url = 'index.php?accion=planificacion_configuracion&pop=1&limpiarSession=1&id='+id;
		popup("ConfiguracionPlanificacion",url,'600','500');
	}
}

function agregarPlanificacion(id, aquien) {	
	var res = confirm('¿Desea agregar una planificación\?');
	if(res) {				
		if(aquien=='actividad'){
			url = 'index.php?accion=planificacion_configuracion&pop=1&limpiarSession=1&id_actividad='+id;
		} else {
			url = 'index.php?accion=planificacion_configuracion&pop=1&limpiarSession=1&id_actividad_envio='+id;
		}
		popup("ConfiguracionPlanificacion",url,'600','500');
	}
}

function apretarBotonConfigurarEnviador(id) {
	obj_tecnologia_enviador = document.getElementById("id_tecnologia_enviador");	
	id_tecnologia_enviador = obj_tecnologia_enviador.options[obj_tecnologia_enviador.selectedIndex].value;
	obj_nombre_enviador = document.getElementById("nombre_enviador");
	
	if(id != undefined){				
		document.getElementById('id_act_env').value = id;
	} else {
		// Valido que se haya seleccionado un tipo de tecnología de enviador
		if(id_tecnologia_enviador==0){
			window.alert('Debe seleccionar una tecnología de envio');
			return;
		}
		// Valido que se haya establecido un nombre para el enviador
		if(obj_nombre_enviador.value == "") {
			window.alert('Debe establecer un nombre para el enviador');
			return;
		}	
	}
	// Hago submit para que se ejecute la función ConfigurarEnviador en la acción
	botonConf = document.getElementById("btnConfigurarEnviador");
	botonConf.click();	
}

function borrarEnviador(id) {
	apretarBotonRecargar();
	var res = confirm('¿Borrar el enviador\?');			
	if (res) {
		id_actividad = document.getElementById('id_actividad').value;		
		location.href = 'index.php?accion=actividad_envio_del&id='+id+'&id_actividad='+id_actividad;
	}
}

function setEliminarDestino(idDestino) {
	hidden_field = document.getElementById('js_method_action');
	hidden_field.name = "btnEliminarDestinoFTP";
	hidden_id_fila = document.getElementById('id_fila_borrar');
	hidden_id_fila.value = idDestino;
	document.forms[0].submit();
}

function setEditarDestino(idDestino) {
	hidden_field = document.getElementById('js_method_action');
	hidden_field.name = "btnEditarDestinoFTP";
	hidden_id_fila = document.getElementById('id_fila_borrar');
	hidden_id_fila.value = idDestino;
	document.forms[0].submit();
}

function borrarActividad(id) {
	var res = confirm('¿Borrar la actividad\?');			
	if (res) {				
		location.href = 'index.php?accion=actividad_del&id='+id;
	}
}

function ejecutarActividadAhora(id) {
	var res = confirm('¿Ejecutar la actividad\?');			
	if (res) {				
		location.href = 'index.php?accion=actividad_ejecutar_ahora&id='+id;
	}	
	
}

