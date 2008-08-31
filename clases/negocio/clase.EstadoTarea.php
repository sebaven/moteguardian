<?
/**
 * @author cgalli
 */
class EstadoTarea extends AbstractEntity
{
    /**
     * Nombre de la tabla sobre a la cual accede la clase
     * @access protected
     * @var string
     */
    var $_tablename = 'estado_tarea';

    /**
     * Nombre de los campos, menos el campo id
     * @access protected
     * @var array
     */
    var $_fields = array
    (
	    'id_tarea'=>'int',
    	'id_informacion_estado'=>'int',
    	'nombre_original'=>'varchar',
    	'nombre_actual'=>'varchar',
    	'ubicacion'=>'varchar',
    	'baja_logica'=>'boolean',
    	'timestamp_ingreso'=>'date',
    	'fallo'=>'boolean',
    	'ultimo'=>'boolean',
    	'esperando_envio'=>'boolean',
    	'pid' => 'int'
    );

    var $id;
    var $id_tarea;
    var $id_informacion_estado;
    var $nombre_original;
    var $nombre_actual;
    var $ubicacion;
    var $baja_logica;
    var $timestamp_ingreso;
    var $fallo;
    var $ultimo;
    var $esperando_envio;
    var $pid;
}

?>