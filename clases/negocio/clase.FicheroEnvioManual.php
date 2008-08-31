<?
/**
 * @date 28/07/2008
 * @version 1.0
 * @author aamantea
 */
class FicheroEnvioManual extends AbstractEntity
{
    /**
     * Nombre de la tabla sobre a la cual accede la clase
     * @access protected
     * @var string
     */
    var $_tablename = 'fichero_envio_manual';

    /**
     * Nombre de los campos, menos el campo id
     * @access protected
     * @var array
     */
    var $_fields = array
    (
    	'id_fichero' => 'int',
    	'id_envio' => 'int'	
    );

    var $id;
    var $id_fichero;
    var $id_envio;
}

?>