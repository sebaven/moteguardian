<?
class LogAlarma extends AbstractEntity
{
    /**
     * Nombre de la tabla sobre a la cual accede la clase
     * @access protected
     * @var string
     */
    var $_tablename = 'log_alarma';

    /**
     * Nombre de los campos, menos el campo id
     * @access protected
     * @var array
     */
    var $_fields = array
    (				
		'id_dispositivo_disparador'	=> 'int',
		'timestamp_inicio'		 	=> 'varchar',
		'timestamp_fin'		 		=> 'varchar',			
    	'es_falsa' 					=> 'bool',     
        'baja_logica' => 'int'
    );

    var $id;    
    var $id_dispositivo_disparador;
    var $timestamp_inicio;
    var $timestamp_fin;
    var $es_false;
    var $baja_logica;   

}
?>