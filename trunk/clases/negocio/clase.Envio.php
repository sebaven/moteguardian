<?
include_once BASE_DIR."clases/negocio/clase.HostEnvio.php";
include_once BASE_DIR."clases/dao/dao.HostEnvio.php";


/**
 * @date 28/07/2008
 * @version 1.0
 * @author cgalli
 */
class Envio extends AbstractEntity
{
    /**
     * Nombre de la tabla sobre a la cual accede la clase
     * @access protected
     * @var string
     */
    var $_tablename = 'envio';

    /**
     * Nombre de los campos, menos el campo id
     * @access protected
     * @var array
     */
    var $_fields = array
    (
	    'id_recoleccion' => 'int',
	    'nombre' => 'varchar',
    	'baja_logica' => 'boolean',    	    
    	'inmediato' => 'boolean',
    	'temporal' => 'boolean',
    	'manual' => 'boolean',
    	'habilitado' => 'boolean'    	    
    );

    var $id;        
    var $nombre;    
    var $baja_logica;
    var $inmediato;    
    var $temporal;
    var $habilitado;
    var $manual;
    var $id_recoleccion;

    function agregarHost($id_host) {
    	$hostEnvioDAO = new HostEnvioDAO();
    	$hostEnvio = $hostEnvioDAO->getHostEnvio($id_host, $this->id);
    	if (!isset($hostEnvio)) {
	    	$hostEnvio = new HostEnvio();
			$hostEnvio->id_envio = $this->id;
			$hostEnvio->id_host = $id_host;
			
			$hostEnvio->save();    		     	
    	}
    }
    
    function quitarHost($id_host) {
    	$hostEnvioDAO = new HostEnvioDAO();
    	$hostEnvio = $hostEnvioDAO->getHostEnvio($id_host, $this->id);
    	if (isset($hostEnvio)) $hostEnvio->delete();    	    	
    }    
}

?>