<?
/** 
 * @author cgalli
 */
class MD5TareaRecoleccion extends AbstractEntity
{
    /**
     * Nombre de la tabla sobre a la cual accede la clase
     * @access protected
     * @var string
     */
    var $_tablename = 'md5_tarea_recoleccion';

    /**
     * Nombre de los campos, menos el campo id
     * @access protected
     * @var array
     */
    var $_fields = array
    (
	    'id_recoleccion' => 'int',
    	'md5' => 'varchar'    
    );

    var $id;
    var $id_recoleccion;
    var $md5;
}

?>