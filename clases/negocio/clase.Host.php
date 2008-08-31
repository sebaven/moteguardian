<?
/**
 * @date 13/05/2008
 * @version 1.0
 * @author aamantea
 */
class Host extends AbstractEntity
{
    /**
     * Nombre de la tabla sobre a la cual accede la clase
     * @access protected
     * @var string
     */
    var $_tablename = 'host';

    /**
     * Nombre de los campos, menos el campo id
     * @access protected
     * @var array
     */
    var $_fields = array
    (
		'nombre' => 'varchar',
		'id_tecnologia_enviador' => 'int',		
		'descripcion' => 'varchar',
		'baja_logica' => 'boolean',		
    );

    var $id;
    var $nombre;
    var $id_tecnologia_enviador;
    var $descripcion;
    var $baja_logica;
}

?>