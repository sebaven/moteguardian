<?
/**
 * @date 29/05/2008
 * @version 1.0
 * @author mhernandez
 */
class TecnologiaCentral extends AbstractEntity
{
    /**
     * Nombre de la tabla sobre a la cual accede la clase
     * @access protected
     * @var string
     */
    var $_tablename = 'tecnologia_central';

    /**
     * Nombre de los campos, menos el campo id
     * @access protected
     * @var array
     */
    var $_fields = array
    (
      	'nombre' => 'varchar',
    	'baja_logica' => 'boolean',
    	'version' => 'varchar', //TODO: es float pero el framework no lo ofrece como tipo
    	'descripcion' => 'varchar'
    );

    var $id;
    var $nombre;
    var $baja_logica;
    var $version;
    var $descripcion;
}

?>