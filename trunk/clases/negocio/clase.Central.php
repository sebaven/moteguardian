<?
/**
 * @date 13/05/2008
 * @version 1.0
 * @author aamantea
 */
class Central extends AbstractEntity
{
    /**
     * Nombre de la tabla sobre a la cual accede la clase
     * @access protected
     * @var string
     */
    var $_tablename = 'central';

    /**
     * Nombre de los campos, menos el campo id
     * @access protected
     * @var array
     */
    var $_fields = array
    (
    	'id_recoleccion' => 'int',
		'nombre' => 'varchar',
		'id_tecnologia_recolector' => 'int',
		'procesador' => 'varchar',
		'descripcion' => 'varchar',
		'baja_logica' => 'boolean',
		'id_tecnologia_central' => 'int'
    );

    var $id;
    var $nombre;
    var $id_tecnologia_recolector;
    var $id_tecnologia_central;
    var $procesador;
    var $descripcion;
    var $baja_logica;
    var $id_recoleccion;
    
}

?>