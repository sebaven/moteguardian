<?
/**
 * @date 13/05/2008
 * @version 1.0
 * @author glerendegui
 */
class Configuracion extends AbstractEntity
{
    // * Nombre de la tabla sobre a la cual accede la clase
    var $_tablename = 'config';

    // * Nombre de los campos, menos el campo id
    var $_fields = array
    (
      'nombre' => 'varchar',
      'valor' => 'varchar'
    );

    var $id;
    var $nombre;
    var $valor;
    
    // TODO: falta el campo baja_logica
}

?>