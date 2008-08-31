<?
/**
 * @date 28/07/2008
 * @version 1.0
 * @author aamantea
 */
class CentralRecoleccionManual extends AbstractEntity
{
    /**
     * Nombre de la tabla sobre a la cual accede la clase
     * @access protected
     * @var string
     */
    var $_tablename = 'central_recoleccion_manual';

    /**
     * Nombre de los campos, menos el campo id
     * @access protected
     * @var array
     */
    var $_fields = array
    (
    	'id_central' => 'int',
    	'id_recoleccion' => 'int'	
    );

    var $id;
    var $id_central;
    var $id_recoleccion;
}

?>