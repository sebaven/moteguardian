<?
class ItemRonda extends AbstractEntity
{
    /**
     * Nombre de la tabla sobre a la cual accede la clase
     * @access protected
     * @var string
     */
    var $_tablename = 'item_ronda';

    /**
     * Nombre de los campos, menos el campo id
     * @access protected
     * @var array
     */
    var $_fields = array
    (				
		'id_ronda' 		=> 'int',
		'id_sala' 		=> 'int',
		'duracion' 		=> 'int',			
    	'orden' 		=> 'int'      
    );

    var $id;    
    var $id_sala;
    var $id_ronda;
    var $orden;
    var $duracion;
}
?>