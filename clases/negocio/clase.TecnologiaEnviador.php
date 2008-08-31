<?
/**
 * @date 13/05/2008
 * @version 1.0
 * @author aamantea
 */
class TecnologiaEnviador extends AbstractEntity
{
    /**
     * Nombre de la tabla sobre a la cual accede la clase
     * @access protected
     * @var string
     */
    var $_tablename = 'tecnologia_enviador';

    /**
     * Nombre de los campos, menos el campo id
     * @access protected
     * @var array
     */
    var $_fields = array
    (
      	'nombre_tecnologia' => 'varchar',
    	'baja_logica' => 'boolean',
    	'accion' => 'varchar',
    	'clase_handler' => 'varchar'
    );

    var $id;
    var $nombre_tecnologia;
    var $accion;
    var	$baja_logica;
    var $clase_handler;
}

?>