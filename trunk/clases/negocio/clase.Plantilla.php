<?
/**
 * @date 13/05/2008
 * @version 1.0
 * @author aamantea
 */
class Plantilla extends AbstractEntity
{
    /**
     * Nombre de la tabla sobre a la cual accede la clase
     * @access protected
     * @var string
     */
    var $_tablename = 'plantilla';

    /**
     * Nombre de los campos, menos el campo id
     * @access protected
     * @var array
     */
    var $_fields = array
    (
      'nombre' => 'varchar',
      'baja_logica' => 'boolean',
      'temporal' => 'boolean'
    );

    var $id;
    var $nombre;
    var $baja_logica;
    var $temporal;    
}

?>