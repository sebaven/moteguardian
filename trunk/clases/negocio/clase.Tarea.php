<?php
/** 
 * @author cgalli
 */

include_once(BASE_DIR."clases/negocio/clase.ActividadEjecutada.php");
include_once(BASE_DIR."clases/dao/dao.ActividadEnvio.php");

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
    var $baja_logica;
    var $fecha_terminada;
    var $id_recoleccion;
    var $nombre_original;
    var $ubicacion_original;
    var $esperando_envio;
    var $esperando_recoleccion;
    var $tamanio;

    // FIXME: Actividad Ejecutada no va a existir mas!!!
    function recalcularEsperandoEnvio() {
    	// Busco envios a aplicarlo
    	$actividadEjecutada = new ActividadEjecutada($this->id_actividad_ejecutada);
    	if(!$actividadEjecutada->id) return false;
    	
    	$actividadEnvioDAO = new ActividadEnvioDAO();
    	$envios = $actividadEnvioDAO->filterByIdActividad($actividadEjecutada->id_actividad);

    	foreach($envios as $envio) {
			// Busco que haya registro de alguna vez haberse enviado bien
			$sql = " SELECT * FROM estado_tarea et ";
			$sql.= " JOIN datos_proceso dt ON et.id_datos_proceso = dt.id ";
			$sql.= " WHERE dt.id_proceso_actividad_envio = '".$envio->id."' ";
			$sql.= " AND et.fallo = '".FALSE_."' AND et.id_tarea = '".$this->id."' ";
			$rs = $this->_db->leer($sql);
			if(!$rs) return false;
			if($this->_db->numero_filas($rs) == 0) {
				// Ya para este envio falta, no actualizo el pendiente de envio
				return false;
			}
    	}
    	// se enviaron para todos los envios, actualizo
    	$this->esperando_envio = FALSE_;
    	$fecha = new Fecha();
    	$fecha->loadFromNow();
    	$this->fecha_terminada = $fecha->toString();
    	$this->save();
		return true;
    	
	}
    
    
}
?>