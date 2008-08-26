<?
/**
 * @date 28/07/2008
 * @version 1.0
 * @author cgalli
 */
class Envio extends AbstractEntity
{
    /**
     * Nombre de la tabla sobre a la cual accede la clase
     * @access protected
     * @var string
     */
    var $_tablename = 'envio';

    /**
     * Nombre de los campos, menos el campo id
     * @access protected
     * @var array
     */
    var $_fields = array
    (
	    'id_recoleccion' => 'int',
	    'nombre' => 'varchar',
	    'formato_renombrado' => 'varchar',
    	'baja_logica' => 'boolean',    	    
    	'inmediato' => 'boolean',    	    
    	'habilitado' => 'boolean'    	    
    );

    var $id;        
    var $nombre;    
    var $baja_logica;
    var $formato_renombrado;    
    var $inmediato;    
    var $habilitado;
    var $id_recoleccion;    
}

?>