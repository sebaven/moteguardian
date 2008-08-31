<?
include_once BASE_DIR."clases/negocio/clase.Configuracion.php";
/**
 * @date 13/05/2008
 * @version 1.0
 * @author glerendegui
 */
 
class ConfiguracionDAO extends AbstractDAO
{
	function getEntity()
	{
		return new Configuracion();
	}
	
}
?>