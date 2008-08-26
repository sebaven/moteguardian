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
	    'filtro_seleccion_ficheros' => 'varchar',
	    'formato_renombrado' => 'varchar',
    	'baja_logica' => 'boolean',    	    
    	'inmediato' => 'boolean',    	    
    	'habilitado' => 'boolean'    	    
    );

    var $id;    
    var $id_actividad;    
    var $nombre;    
    var $filtro_seleccion_ficheros;    
    var $formato_renombrado;    
    var $baja_logica;
    var $inmediato;    
    var $habilitado;
}

?>