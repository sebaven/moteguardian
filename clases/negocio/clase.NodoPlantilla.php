<?
/**
 * @date 13/05/2008
 * @version 1.0
 * @author aamantea
 */
class NodoPlantilla extends AbstractEntity
{
    /**
     * Nombre de la tabla sobre a la cual accede la clase
     * @access protected
     * @var string
     */
    var $_tablename = 'nodo_plantilla';

    /**
     * Nombre de los campos, menos el campo id
     * @access protected
     * @var array
     */
    var $_fields = array
    (
      'id_nodo_plantilla_anterior' => 'int',
      'id_plantilla' => 'int',
      'id_tipo_filtro' => 'int',
      'nombre' => 'varchar',
      'baja_logica' => 'boolean'
      
    );

    var $id;
    var $id_nodo_plantilla_anterior;
    var $id_plantilla;
    var $id_tipo_filtro;
    var $nombre;
    var $baja_logica;
}

?>