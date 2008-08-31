<?php
/**
 * @author cgalli
 */
class InformacionEstado extends AbstractEntity
{
    /**
     * Nombre de la tabla sobre a la cual accede la clase
     * @access protected
     * @var string
     */
    var $_tablename = 'informacion_estado';

    /**
     * Nombre de los campos, menos el campo id
     * @access protected
     * @var array
     */
    var $_fields = array
    (
		'id_nodo_plantilla'=>'int',
		'id_recoleccion'=>'int',
		'id_envio'=>'int',
		'nombre_estado'=>'varchar'
    );

    var $id;
    var $id_nodo_plantilla;
    var $id_recoleccion;
    var $id_envio;
    var $nombre_estado;
}

?>