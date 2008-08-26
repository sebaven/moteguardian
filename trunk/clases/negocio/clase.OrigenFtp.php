<?
/**
 * @date 13/05/2008
 * @version 1.0
 * @author aamantea
 */
class OrigenFtp extends AbstractEntity
{
    /**
     * Nombre de la tabla sobre a la cual accede la clase
     * @access protected
     * @var string
     */
    var $_tablename = 'origen_ftp';

    /**
     * Nombre de los campos, menos el campo id
     * @access protected
     * @var array
     */
    var $_fields = array
    (
      'direccion_ip' => 'varchar',
      'puerto' => 'int',
      'ubicacion_archivos' => 'varchar',
      'intentos_conexion' => 'int',
      'usuario' => 'varchar',
      'contrasenia' => 'varchar',
      'id_recolector_ftp' => 'int',
      'baja_logica' => 'int',
      'nro_orden' => 'int',
      'modo_pasivo' => 'int',
      'timeout' => 'int'
    );

    var $id;
    var $direccion_ip;
    var $puerto;
    var $ubicacion_archivos;
    var $intentos_conexion;
    var $usuario;
    var $contrasenia;
    var $id_recolector_ftp;
    var $baja_logica;
    var $nro_orden;
    var $modo_pasivo;
    var $timeout;
    
	function OrigenFtp($idRecolectorFtp,$datos) {
		parent::AbstractEntity();
    	$this->id_recolector_ftp = $idRecolectorFtp;
    	$this->direccion_ip = $datos['direccionIP'];
    	$this->puerto = $datos['puerto'];
    	$this->ubicacion_archivos = $datos['ubicacion'];
    	$this->intentos_conexion = $datos['nroIntentos'];
    	$this->usuario = $datos['usuarioFTP'];
    	$this->contrasenia = $datos['passwordFTP'];
    	$this->timeout = $datos['timeout'];
    	
    	if($datos['modo_pasivo'] == "Si") {
    		$this->modo_pasivo = TRUE_;
    	} else $this->modo_pasivo = FALSE_;
    	
    	if(isset($datos['id'])) {
    		$this->id = $datos['id'];
    	}
		if(isset($datos['bajaLogica'])) {
    		$this->baja_logica = $datos['bajaLogica'];
    	}
		if(isset($datos['nroOrden'])) {
    		$this->nro_orden = $datos['nroOrden'];
    	}
    }
}

?>