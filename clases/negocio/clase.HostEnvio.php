<?
/**
 * @date 28/07/2008
 * @version 1.0
 * @author cgalli
 */
class HostEnvio extends AbstractEntity
{
    /**
     * Nombre de la tabla sobre a la cual accede la clase
     * @access protected
     * @var string
     */
    var $_tablename = 'host_envio';

    /**
     * Nombre de los campos, menos el campo id
     * @access protected
     * @var array
     */
    var $_fields = array
    (
    	'id_host' => 'int',
    	'id_envio' => 'int'	
    );

    var $id;
    var $id_host;
    var $id_envio;
}

?>