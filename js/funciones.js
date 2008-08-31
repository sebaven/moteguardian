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
	//eval ("newwin_" + nombre + ".focus();");
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
	procesadorCentral = escape(document.getElementById("procesador").value);
	descripcionCentral = escape(document.getElementById("descripcion").value);
		
	nombreAccion = document.getElementById("accion_tecnologia_recolector").value;
	idCentral = document.getElementById("id_central").value;
	url = "index.php?accion=" + nombreAccion + "&pop=1&id_tecnologia_recolector=" + id_tecnologia_recolector + "&tecnologia=" + valorTecnologia + "&nombreCentral=" + nombreCentral;
	url = url + "&procesadorCentral=" + procesadorCentral + "&idCentral=" + idCentral + "&id_tecnologia_central=" + id_tecnologia_central + "&descripcionCentral=" + descripcionCentral;
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
	rec_local = document.getElementById('recoleccion_local_seleccionada').value;
	if (rec_local == '1') {
		alert("No se puede eliminar el origen de una recolección local.");
		return true;
	}
	hidden_field = document.getElementById('js_method_action');
	hidden_field.name = "btnEliminarOrigenFTP";
	hidden_id_fila = document.getElementById('id_fila_borrar');
	hidden_id_fila.value = idOrigen;
	document.forms[0].submit();
}

function setEditarOrigen(idOrigen) {
	rec_local = document.getElementById('recoleccion_local_seleccionada').value;
	if (rec_local == '1') {
		alert("No se puede editar un origen para una recolección local");
		return true;
	}
	hidden_field = document.getElementById('js_method_action');
	hidden_field.name = "btnEditarOrigenFTP";
	hidden_id_fila = document.getElementById('id_fila_borrar');
	hidden_id_fila.value = idOrigen;
	document.forms[0].submit();
}

/*-----------------------------------------------------------
 * Tecnologías de central
 ------------------------------------------------------------*/
function mostrarAgregarVersion()
{
	document.getElementById('agregar_version').style.display='block';
	document.getElementById('creacion_tecnologia').style.display='none';
	document.getElementById('accion_tecnologia_central').value='agregarVersion';
}

function mostrarCrearTecnologia()
{
	document.getElementById('agregar_version').style.display='none';
	document.getElementById('creacion_tecnologia').style.display='block';
	document.getElementById('accion_tecnologia_central').value='crearTecnologia';
}

function borrarTecnologiaCentral(id)
{
	var res = confirm('¿Borrar la Tecnología de central\?');	
	if (res)			
		location.href = 'index.php?accion=tecnologia_central_remove&id_tecnologia_central=' + id;		
}

function editarTecnologiaCentral(id)
{
	location.href = 'index.php?accion=tecnologia_central_admin&id_tecnologia_central=' + id; 
}

/*-----------------------------------------------------------
 * Actividad
 ------------------------------------------------------------*/
function seleccionarActividad(id)
{   
	location.href = 'index.php?accion=actividad_new&id_actividad=' + id;
}

function borrarPlanificacion(id){
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
		if(aquien=='recoleccion'){
			url = 'index.php?accion=planificacion_configuracion&pop=1&limpiarSession=1&id_recoleccion='+id;
		} else {
			url = 'index.php?accion=planificacion_configuracion&pop=1&limpiarSession=1&id_envio='+id;
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
	env_local = document.getElementById('envio_local_seleccionado').value;
	if (env_local == '1') {
		alert("No se puede eliminar el destino de un envío local");
		return true;
	}
	hidden_field = document.getElementById('js_method_action');
	hidden_field.name = "btnEliminarDestinoFTP";
	hidden_id_fila = document.getElementById('id_fila_borrar');
	hidden_id_fila.value = idDestino;
	document.forms[0].submit();
}

function setEditarDestino(idDestino) {
	env_local = document.getElementById('envio_local_seleccionado').value;
	if (env_local == '1') {
		alert("No se puede editar un destino para un envío local");
		return true;
	}
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

function ejecutarRecoleccionAhora(id) {
	var res = confirm('¿Ejecutar la recolección\?');			
	if (res) {				
		location.href = 'index.php?accion=recoleccion_ejecutar_ahora&id_recoleccion='+id;
	}	
	
}

function mostrarOcultado(id){
	div = document.getElementById(id);
	if(div.style.display=='none'){
		div.style.display='block';
	} else {
		div.style.display='none';
	}
}

function mostrarEjecucionesDeCentral(idRecoleccion, idCentral){
	divEjecucionesCentral = document.getElementById('ejecuciones_central_'+idRecoleccion+'_'+idCentral);
	
	if(divEjecucionesCentral.style.display=='none') divEjecucionesCentral.style.display='block';
	else divEjecucionesCentral.style.display='none';
}

function agregarInmediatamente() {
	reposteando = document.getElementById('is_repost');
	reposteando.value = "yes";
	apretarBotonRecargar();
}

function habilitarEnvio(idEnv) {
	//alert('habilitado' + idEnv);
	//is_dis = document.getElementById('habilitado' + idEnv);
	//alert(is_dis);
	//if (is_dis.disabled) {
	//	alert("Esta deshabilitado!!");
	//}
	return true;
}


/*-----------------------------------------------------------
 * Host
 ------------------------------------------------------------*/

function configurarEnviador(){
	// Se obtienen los datos de la tecnologia del recolector
	obj_tecnologia_envio = document.getElementById("id_tecnologia_envio");
	id_tecnologia_envio = obj_tecnologia_envio.options[obj_tecnologia_envio.selectedIndex].value;
	valorTecnologia = obj_tecnologia_envio.options[obj_tecnologia_envio.selectedIndex].text;

	nombreHost = escape(document.getElementById("nombre").value);
	descripcionHost = escape(document.getElementById("descripcion").value);
		
	nombreAccion = document.getElementById("accion_tecnologia_envio").value;
	idHost = document.getElementById("id_host").value;
	url = "index.php?accion=" + nombreAccion + "&pop=1&id_tecnologia_envio=" + id_tecnologia_envio + "&tecnologia=" + valorTecnologia + "&nombreHost=" + nombreHost;
	url = url + "&idHost=" + idHost + "&descripcionHost=" + descripcionHost;
	popup("ConfiguracionEnviador",url,'700','500');
	return false;
}

function seleccionarHost(id)
{
	location.href = 'index.php?accion=host_mod&id=' + id;
}


function borrarHost(id)
{
	var res = confirm('¿Borrar el Host\?')	
	if (res)
		location.href = 'index.php?accion=host_del&id='+id;
}

/*-----------------------------------------------------------
 * Recoleccion
 ------------------------------------------------------------*/

function quitarCentral(id){
	var res = confirm('¿Desea quitarle la central a la recoleccion\?')	
	if (res) {
		idRecoleccion = document.getElementById('id_recoleccion').value;
		location.href = 'index.php?accion=liberar_central&id='+id+'&id_recoleccion='+idRecoleccion;
	}
}

function eliminarRecoleccion(id) {
	var res = confirm('¿Desea eliminar la recoleccion\?')	
	if (res) {		
		location.href = 'index.php?accion=recoleccion_del&id='+id;
	}
}

function editarRecoleccion(id) {
	location.href = 'index.php?accion=recoleccion_conf&id_recoleccion='+id;
}



function quitarCentralFromRecManual(idCentral){
	var res = confirm('¿Desea quitarle la central a la recoleccion manual\?')	
	if (res) {
		idRecoleccion = document.getElementById('id_recoleccion_manual').value;
		nombreRecoleccion = document.getElementById('nombre_recoleccion_manual').value;		
		location.href = 'index.php?accion=liberar_central_rec_manual&id_central='+idCentral+'&id_recoleccion='+idRecoleccion+'&nombre_recoleccion_manual='+nombreRecoleccion;
	}
}

/*-----------------------------------------------------------
 * Envio
 ------------------------------------------------------------*/
 
function editarEnvio(id) {
	location.href = 'index.php?accion=envio_adm&id_envio='+id;
}

function eliminarEnvio(id) {
	var res = confirm('¿Desea eliminar el envio\?');	
	if (res) {		
		location.href = 'index.php?accion=envio_del&id='+id;
	}
}

function quitarFicheroFromEnvManual(idFichero){
	var res = confirm('¿Desea quitar el fichero del envío manual\?')	
	if (res) {
		idEnvio = document.getElementById('id_envio_manual').value;
		nombreEnvio = document.getElementById('nombre_envio_manual').value;
		location.href = 'index.php?accion=liberar_fichero_env_manual&id_fichero='+idFichero+'&id_envio='+idEnvio+'&nombre_envio='+nombreEnvio;
	}
}

/*------------------------------------------------------------
 * Circuitos Cerrados
 *------------------------------------------------------------*/
 
function mostrarDetalles(id) {
	divDetalles = document.getElementById('detalles_'+id);
	if(divDetalles.style.display == 'none') {
		divDetalles.style.display = 'block';		
	} else {
		divDetalles.style.display = 'none';
	}
}

function cambiarHabilitacion(id, aquien) {	
	
}


/*------------------------------------------------------------
 * Recolecciones y envíos en curso
 *------------------------------------------------------------*/
 
 function cancelarTarea(id) {
	var res = confirm('¿Desea cancelar esta tarea\?')	
	if (res) {
		location.href = 'index.php?accion=cancelar_tarea&id_tarea='+id;
	} 	
 }
