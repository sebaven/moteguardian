<?
/**
 * @date 13/05/2008
 * @version 1.0
 * @author aamantea
 */
class TipoFiltro extends AbstractEntity
{
    /**
     * Nombre de la tabla sobre a la cual accede la clase
     * @access protected
     * @var string
     */
    var $_tablename = 'tipo_filtro';

    /**
     * Nombre de los campos, menos el campo id
     * @access protected
     * @var array
     */
    var $_fields = array
    (
      'nombre' => 'varchar',
      'accion' => 'varchar',
      'baja_logica' => 'boolean',
      'clase_handler' => 'varchar'
    );

    var $id;
    var $nombre;
    var $accion;
    var $baja_logica;
    var $clase_handler;
}

?>