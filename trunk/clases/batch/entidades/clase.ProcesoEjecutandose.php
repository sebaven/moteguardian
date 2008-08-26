<?
/**
 * @date 20/05/2008
 * @version 1.0
 * @author glerendegui
 */

class ProcesoEjecutandose extends AbstractEntity
{
    /**
     * Nombre de la tabla sobre a la cual accede la clase
     * @access protected
     * @var string
     */
    var $_tablename = 'proceso_ejecutandose';

    /**
     * Nombre de los campos, menos el campo id
     * @access protected
     * @var array
     */
    var $_fields = array
    (
      'id_recoleccion' => 'varchar',
      'tipo' => 'int',
      'baja_logica' => 'boolean'
    );

    var $id;
    var $id_actividad;
    var $tipo;
    var $baja_logica;
}

?>