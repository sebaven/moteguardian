<?
class Guardia extends AbstractEntity
{
    /**
     * Nombre de la tabla sobre a la cual accede la clase
     * @access protected
     * @var string
     */
    var $_tablename = 'guardia';

    /**
     * Nombre de los campos, menos el campo id
     * @access protected
     * @var array
     */
    var $_fields = array
    (	   
		'id_usuario'		=> 'int',
		'nombre' 			=> 'varchar',
		'codigo_tarjeta'	=> 'varchar'    	      
    );

    var $id;
    var $nombre;
    var $id_usuario;
    var $codigo_tarjeta;    
}
?>