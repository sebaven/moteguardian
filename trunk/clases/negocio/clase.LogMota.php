<?
class LogMota extends AbstractEntity
{
    /**
     * Nombre de la tabla sobre a la cual accede la clase
     * @access protected
     * @var string
     */
    var $_tablename = 'log_mota';

    /**
     * Nombre de los campos, menos el campo id
     * @access protected
     * @var array
     */
    var $_fields = array
    (				
		'id_mota'	=> 'int',
		'timestamp_inicio'	=> 'varchar',
		'timestamp_fin'	=> 'varchar' ,
        'baja_logica' => 'int'
    );

    var $id;
    var $id_mota;
    var $timestamp_inicio;    
    var $timestamp_fin;    
    var $baja_logica;   
}
?>