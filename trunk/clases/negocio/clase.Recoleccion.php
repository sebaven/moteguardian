<?
/**
 * @date 28/07/2008
 * @version 1.0
 * @author cgalli
 */
class Recoleccion extends AbstractEntity
{
    /**
     * Nombre de la tabla sobre a la cual accede la clase
     * @access protected
     * @var string
     */
    var $_tablename = 'recoleccion';

    /**
     * Nombre de los campos, menos el campo id
     * @access protected
     * @var array
     */
    var $_fields = array
    (
	    'id_actividad' => 'int',
	    'nombre' => 'varchar',
    	'formato_renombrado_recoleccion' => 'varchar',
    	'baja_logica' => 'boolean',   	    	    
    	'temporal' => 'boolean',
    	'manual' => 'boolean',
    	'habilitado' => 'boolean'    	    
    );

    var $id;    
    var $id_actividad;    
    var $nombre;    
    var $formato_renombrado_recoleccion;    
    var $baja_logica;
    var $temporal;    
    var $habilitado;
    var $manual;
}

?>