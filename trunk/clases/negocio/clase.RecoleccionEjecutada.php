<?php
/**
 * @author cgalli
 */
class RecoleccionEjecutada extends AbstractEntity
{
    /**
     * Nombre de la tabla sobre a la cual accede la clase
     * @access protected
     * @var string
     */
    var $_tablename = 'recoleccion_ejecutada';

    /**
     * Nombre de los campos, menos el campo id
     * @access protected
     * @var array
     */
    var $_fields = array
    (
	    'id_recoleccion' => 'int',
    	'fecha' => 'varchar',
    	'baja_logica' => 'boolean',
    	'modulos' => 'text',
    	'pid' => 'int'    	
    );

    var $id;
    var $id_recoleccion;
    var $fecha;
    var $baja_logica;
    var $modulos;
    var $pid;
}
?>