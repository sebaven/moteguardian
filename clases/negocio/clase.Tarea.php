<?php
include_once BASE_DIR."clases/negocio/clase.RecoleccionEjecutada.php";
/** 
 * @author cgalli
 */

class Tarea extends AbstractEntity
{
    /**
     * Nombre de la tabla sobre a la cual accede la clase
     * @access protected
     * @var string
     */
    var $_tablename = 'tarea';

    /**
     * Nombre de los campos, menos el campo id
     * @access protected
     * @var array
     */
    var $_fields = array
    (
    	'id_recoleccion_ejecutada' => 'int',
    	'id_central' => 'int',
    	'baja_logica' => 'boolean',
    	'fecha_terminada' => 'varchar',
    	'id_recoleccion' => 'int', // Es calculable a partir de id_recoleccion_ejecutada, pero se conserva por cuestiones de performance -> ver TareaDAO->archivoYaRecolectado
    	'nombre_original' => 'varchar',
    	'ubicacion_original' => 'varchar',
    	'esperando_envio' => 'boolean',
    	'esperando_recoleccion' => 'boolean',
    	'tamanio' => 'int'    
    );

    var $id;
    var $id_recoleccion_ejecutada;
    var $id_central;
    var $baja_logica;
    var $fecha_terminada;
    var $id_recoleccion;
    var $nombre_original;
    var $ubicacion_original;
    var $esperando_envio;
    var $esperando_recoleccion;
    var $tamanio;

    function recalcularEsperandoEnvio($masEnvios) {
    	// Busco envios a aplicarlo
    	$recoleccionEjecutada = new RecoleccionEjecutada($this->id_recoleccion_ejecutada);
    	if(!$recoleccionEjecutada->id) return false;
    	
    	$envioDAO = new EnvioDAO();
    	$envios = $envioDAO->filterByField("id_recoleccion",$recoleccionEjecutada->id_recoleccion);

    	foreach($envios as $envio) {
			// Busco que haya registro de que alguna vez se haya enviado bien
			$sql = " SELECT * FROM estado_tarea et ";
			$sql.= " JOIN informacion_estado ie ON et.id_informacion_estado = ie.id ";
			$sql.= " WHERE ie.id_envio = '".$envio->id."' ";
			$sql.= " AND et.fallo = '".FALSE_."' AND et.id_tarea = '".$this->id."' ";
			$rs = $this->_db->leer($sql);
			if(!isset($rs)) return false;
			if($this->_db->numero_filas($rs) == 0) {
				// Ya para este envio falta, no actualizo el pendiente de envio
				return false;
			}
    	}
    	// se enviaron para todos los envios, actualizo
    	$this->esperando_envio = $masEnvios;
    	$fecha = new Fecha();
    	$fecha->loadFromNow();
    	$this->fecha_terminada = $fecha->toString();
    	$this->save();
		return true;
    	
	}
    
	function idToString($id=0) {
    	if(empty($id)) $id=$this->id;
    	return sprintf("%06d",$id);
    }
    
}
?>