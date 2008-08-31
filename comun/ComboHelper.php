<?
include_once BASE_DIR ."clases/dao/dao.Usuario.php";
include_once BASE_DIR ."clases/dao/dao.Rol.php";
include_once BASE_DIR ."clases/dao/dao.TecnologiaRecolector.php";
include_once BASE_DIR ."clases/dao/dao.TipoFiltro.php";
include_once BASE_DIR . 'clases/dao/dao.Central.php';
include_once BASE_DIR . 'clases/dao/dao.Plantilla.php';
include_once BASE_DIR . 'clases/dao/dao.TecnologiaEnviador.php';
include_once BASE_DIR . 'clases/dao/dao.TecnologiaCentral.php';

function ComboUsuario($first=true,$text=''){
	$usuarioDAO = new UsuarioDAO();
	return PresentationUtil::getCombo($usuarioDAO->getAll("usuario"), "usuario",$first,$text);
}

function ComboRol($first=true,$text=''){
	$rolDAO = new RolDAO();
	return PresentationUtil::getCombo($rolDAO->getAll("descripcion"), "descripcion",$first,$text);
}

function ComboTipoFiltro($first=true, $text='')
{
	$tipoFiltroDAO = new TipoFiltroDAO();
	return PresentationUtil::getCombo($tipoFiltroDAO->getAll('nombre'),'nombre',$first,$text);
}

function ComboTecnologiaRecoleccion($first=true, $text=''){
	$tecnologiaRecolectorDAO = new TecnologiaRecolectorDAO();
	return PresentationUtil::getCombo($tecnologiaRecolectorDAO->getAll("nombre_tecnologia"),"nombre_tecnologia",$first,$text);
}

function ComboNombreTecnologiaCentral($first=false, $text='') {
	$tecnologiaCentralDAO = new TecnologiaCentralDAO();
	return PresentationUtil::getCombo($tecnologiaCentralDAO->getDistinctNombres(), "id", $first, $text);
}

function ComboLuegoTransferencia(){
	$luegoTransferencia = array( 0 => PropertiesHelper::GetKey("recolectorFTP.soloCopiar"),1 => PropertiesHelper::GetKey("recolectorFTP.borrar") );
	return $luegoTransferencia;
}

function ComboHoras(){	
	for($i = 0; $i <= 23; $i++) {
		if($i<10){
			$luegoTransferencia["0".$i]="0".$i;			
		} else {
			$luegoTransferencia[$i]=$i;
		}
	}
	return $luegoTransferencia;
}

function ComboMinutos(){	
	for($i = 0; $i <= 59; $i++) {
		if($i<10){
			$luegoTransferencia["0".$i]="0".$i;			
		} else {
			$luegoTransferencia[$i]=$i;
		}
	}
	return $luegoTransferencia;
}

function ComboDias(){
	$luegoTransferencia = array (CONST_DIA_LUNES => CONST_DIA_LUNES,
								CONST_DIA_MARTES  => CONST_DIA_MARTES,
								CONST_DIA_MIERCOLES => CONST_DIA_MIERCOLES,
								CONST_DIA_JUEVES	=> CONST_DIA_JUEVES, 
								CONST_DIA_VIERNES => CONST_DIA_VIERNES, 
								CONST_DIA_SABADO  => CONST_DIA_SABADO,
								CONST_DIA_DOMINGO => CONST_DIA_DOMINGO);
	return $luegoTransferencia;		
}

function ComboCentral($first=false, $text=""){
	$centralDAO = new CentralDAO();
	return PresentationUtil::getCombo($centralDAO->getAll(), "nombre",$first, $text);
}

function ComboPlantillaRecoleccion($first=false){
	$plantillaDAO = new PlantillaDAO();
	return PresentationUtil::getCombo($plantillaDAO->getAll(), "nombre",$first);
}

function ComboTecnologiaEnviador($first=false){
	$tecnologiaEnviadorDAO = new TecnologiaEnviadorDAO();
 	return PresentationUtil::getCombo($tecnologiaEnviadorDAO->getAll(), "nombre_tecnologia",$first,NULL);	
}

function ComboTecnologiaCentral($first=false, $text=''){
	$tecnologiaCentralDAO = new TecnologiaCentralDAO();
 	return PresentationUtil::getCombo($tecnologiaCentralDAO->getAllNombreVersion(), "nombre_version", $first, $text);	
}
	
function ComboHost($first=false,$text=''){
	$hostDAO = new HostDAO();
	return PresentationUtil::getCombo($hostDAO->getAll("nombre"), "nombre", $first, $text);	
}

function ComboRecoleccion($first=false,$text=''){
	$recoleccionDAO = new RecoleccionDAO();
	return PresentationUtil::getCombo($recoleccionDAO->getAll("nombre"), "nombre", $first, $text);	
}

function ComboRecoleccionesSinAsignar() {
	$recoleccionDAO = new RecoleccionDAO();
	return PresentationUtil::getCombo($recoleccionDAO->getRecoleccionesSinAsignar(),"nombre",false);
}

function ComboRecoleccionesAgregadas($id) {
	$recoleccionDAO = new RecoleccionDAO();
	return PresentationUtil::getCombo($recoleccionDAO->getRecoleccionesAgregadas($id),"nombre",false);
}

function ComboCentralesDeRecoleccion($id_recoleccion) {
	$centralDAO = new CentralDAO();
	return PresentationUtil::getCombo($centralDAO->getCentralesByRecoleccion($id_recoleccion), "nombre", false);
}

function ComboHostsSinAsignar($id_envio) {
	$hostDAO = new HostDAO();
	return PresentationUtil::getCombo($hostDAO->getHostsNoAsignadosAEnvio($id_envio), "nombre", false);	
}

function ComboHostsSinAsignarEnvioManual($id_envio) {
	$hostDAO = new HostDAO();
	return PresentationUtil::getCombo($hostDAO->getHostsNoAsignadosAEnvioManual($id_envio), "nombre", false);	
}

function ComboHostsAsignados($id_envio) {
	$hostDAO = new HostDAO();
	return PresentationUtil::getCombo($hostDAO->getHostsAsignadosAEnvio($id_envio), "nombre", false);
}

?>
