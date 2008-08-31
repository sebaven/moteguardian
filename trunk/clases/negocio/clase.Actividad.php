<?
/**
 * @date 13/05/2008
 * @version 1.0
 * @author aamantea
 */
class Actividad extends AbstractEntity
{
    /**
     * Nombre de la tabla sobre a la cual accede la clase
     * @access protected
     * @var string
     */
    var $_tablename = 'actividad';

    /**
     * Nombre de los campos, menos el campo id
     * @access protected
     * @var array
     */
    var $_fields = array
    (
	    'id_plantilla' => 'int',
	    'nombre' => 'varchar',
    	'temporal' => 'boolean',
    	'baja_logica' => 'boolean'    	    
    );

    var $id;
    var $id_plantilla;    
    var $nombre;    
    var $temporal;
    var $baja_logica;    
}

?>