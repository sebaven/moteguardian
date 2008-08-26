<?
/**
 * @date 13/05/2008
 * @version 1.0
 * @author aamantea
 */
class RecolectorFtp extends AbstractEntity
{
    /**
     * Nombre de la tabla sobre a la cual accede la clase
     * @access protected
     * @var string
     */
    var $_tablename = 'recolector_ftp';

    /**
     * Nombre de los campos, menos el campo id
     * @access protected
     * @var array
     */
    var $_fields = array
    (
      'id_central' => 'int',
      'destino_local' => 'varchar',
      'busqueda_recursiva' => 'int',
      'borrar_archivos' => 'int',
      'firma_SHA' => 'int',
      'tamanio' => 'int'
    );

    var $id;
    var $id_central;
    var $destino_local;
    var $busqueda_recursiva;
    var $borrar_archivos;
    var $firma_SHA;
    var $tamanio;
	
    //TODO: no tiene baja logica
}

?>