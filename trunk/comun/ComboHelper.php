<?
include_once BASE_DIR ."clases/dao/dao.Usuario.php";
include_once BASE_DIR ."clases/dao/dao.Rol.php";

function ComboUsuario($first=true,$text=''){
	$usuarioDAO = new UsuarioDAO();
	return PresentationUtil::getCombo($usuarioDAO->getAll("usuario"), "usuario",$first,$text);
}

function ComboRol($first=true,$text=''){
	$rolDAO = new RolDAO();
	return PresentationUtil::getCombo($rolDAO->getAll("descripcion"), "descripcion",$first,$text);
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

?>
