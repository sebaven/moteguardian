<?
class Ronda extends AbstractEntity
{
    /**
     * Nombre de la tabla sobre a la cual accede la clase
     * @access protected
     * @var string
     */
    var $_tablename = 'ronda';
    /**
     * Nombre de los campos, menos el campo id
     * @access protected
     * @var array
     */
    var $_fields = array
    (	   
		'id_guardia'		=> 'int',
        'baja_logica' => 'int'
    );

    var $id;
    var $id_guardia;            
    var $baja_logica;   
}
?>