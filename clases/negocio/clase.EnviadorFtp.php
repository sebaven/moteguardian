<?
/**
 * @date 13/05/2008
 * @version 1.0
 * @author aamantea
 */
class EnviadorFtp extends AbstractEntity
{
    /**
     * Nombre de la tabla sobre a la cual accede la clase
     * @access protected
     * @var string
     */
    var $_tablename = 'enviador_ftp';


    /**
     * Nombre de los campos, menos el campo id
     * @access protected
     * @var array
     */
    var $_fields = array
    (
      	'id_host' => 'int',
       	'formato_renombrado' => 'varchar',
      	'baja_logica' => 'int'     
    );

    var $id;
    var $id_host;
    var $formato_renombrado;
    var $baja_logica;    
}

?>