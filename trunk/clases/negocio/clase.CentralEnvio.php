<?
/**
 * @date 28/07/2008
 * @version 1.0
 * @author cgalli
 */
class CentralEnvio extends AbstractEntity
{
    /**
     * Nombre de la tabla sobre a la cual accede la clase
     * @access protected
     * @var string
     */
    var $_tablename = 'central_envio';

    /**
     * Nombre de los campos, menos el campo id
     * @access protected
     * @var array
     */
    var $_fields = array
    (
    	'id_central' => 'int',
    	'id_envio' => 'int'	
    );

    var $id;
    var $id_central;
    var $id_envio;
}

?>