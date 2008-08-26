<?
/**
 * @date 13/05/2008
 * @version 1.0
 * @author aamantea
 */
class TecnologiaRecolector extends AbstractEntity
{
    /**
     * Nombre de la tabla sobre a la cual accede la clase
     * @access protected
     * @var string
     */
    var $_tablename = 'tecnologia_recolector';

    /**
     * Nombre de los campos, menos el campo id
     * @access protected
     * @var array
     */
    var $_fields = array
    (
		'nombre_tecnologia' => 'varchar',
		'accion' => 'varchar',
		'clase_handler' => 'varchar',
    	'baja_logica' => 'boolean'   
    );

    var $id;
    var $nombre_tecnologia;
    var $accion;
    var $clase_handler;
    var $baja_logica;
}

?>