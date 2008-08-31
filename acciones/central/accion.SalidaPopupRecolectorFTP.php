<?php
include_once BASE_DIR ."clases/negocio/clase.Configuracion.php";
include_once BASE_DIR ."clases/dao/dao.Configuracion.php";
include_once BASE_DIR ."clases/service/clase.Logger.php";
include_once BASE_DIR ."comun/defines_acciones_logger.php";

/**
 * @author aamantea
 */
class SalidaPopupRecolectorFTP extends Action {
	var $tpl = "tpl/central/tpl.SalidaPopupRecolectorFTP.php";
}
?>