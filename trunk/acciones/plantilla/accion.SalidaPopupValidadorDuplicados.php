<?php
include_once BASE_DIR ."clases/negocio/clase.Configuracion.php";
include_once BASE_DIR ."clases/dao/dao.Configuracion.php";
include_once BASE_DIR ."clases/service/clase.Logger.php";
include_once BASE_DIR ."comun/defines_acciones_logger.php";


class SalidaPopupValidadorDuplicados extends Action {
	var $tpl = "tpl/general/tpl.SalidaPopupValidadorDuplicados.php";
}
?>