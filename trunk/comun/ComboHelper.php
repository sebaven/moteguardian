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

function ComboLuegoTransferencia(){
	$luegoTransferencia = array( 0 => PropertiesHelper::GetKey("recolectorFTP.soloCopiar"),1 => PropertiesHelper::GetKey("recolectorFTP.borrar") );
	return $luegoTransferencia;
}

function ComboHoras(){
	$luegoTransferencia = array( 0 => PropertiesHelper::GetKey("planificacion.horas") );
	for($i = 0; $i <= 23; $i++) {
		if($i<10){
			$luegoTransferencia[$i]="0".$i;			
		} else {
			$luegoTransferencia[$i]=$i;
		}
	}
	return $luegoTransferencia;
}

function ComboMinutos(){
	$luegoTransferencia = array( 0 => PropertiesHelper::GetKey("planificacion.minutos") );
	for($i = 0; $i <= 59; $i++) {
		if($i<10){
			$luegoTransferencia[$i]="0".$i;			
		} else {
			$luegoTransferencia[$i]=$i;
		}
	}
	return $luegoTransferencia;
}

function ComboDias(){
	$luegoTransferencia = array (0 =>PropertiesHelper::GetKey('planificacion.dias'), 
								CONST_DIA_LUNES => PropertiesHelper::GetKey('dias.lunes'),
								CONST_DIA_MARTES  => PropertiesHelper::GetKey('dias.martes'),
								CONST_DIA_MIERCOLES => PropertiesHelper::GetKey('dias.miercoles'),
								CONST_DIA_JUEVES	=> PropertiesHelper::GetKey('dias.jueves'), 
								CONST_DIA_VIERNES => PropertiesHelper::GetKey('dias.viernes'), 
								CONST_DIA_SABADO  =>	PropertiesHelper::GetKey('dias.sabado'),
								CONST_DIA_DOMINGO => PropertiesHelper::GetKey('dias.domingo'));
	return $luegoTransferencia;		
}

function ComboCentral($first=false){
	$centralDAO = new CentralDAO();
	return PresentationUtil::getCombo($centralDAO->getAll(), "nombre",$first);
}

function ComboPlantillaRecoleccion($first=false){
	$plantillaDAO = new PlantillaDAO();
	return PresentationUtil::getCombo($plantillaDAO->getAll(), "nombre",$first);
}

function ComboTecnologiaEnviador($first=false){
	$tecnologiaEnviadorDAO = new TecnologiaEnviadorDAO();
 	return PresentationUtil::getCombo($tecnologiaEnviadorDAO->getAll(), "nombre_tecnologia",$first);	
}

function ComboTecnologiaCentral($first=false){
	$tecnologiaCentralDAO = new TecnologiaCentralDAO();
 	return PresentationUtil::getCombo($tecnologiaCentralDAO->getAll(), "nombre",$first);	
}
?>
