<?

/**
 * @date 13/05/2008
 * @version 1.0
 * @author aamantea
 */
class Planificacion extends AbstractEntity
{
    /**
     * Nombre de la tabla sobre a la cual accede la clase
     * @access protected
     * @var string
     */
    var $_tablename = 'planificacion';

    /**
     * Nombre de los campos, menos el campo id
     * @access protected
     * @var array
     */
    var $_fields = array
    (
		'dia_absoluto' => 'date',
		'dia_semana' => 'varchar',
		'hora' => 'varchar',
		'id_recoleccion' => 'int',
		'id_envio' => 'int',
    	'baja_logica' => 'boolean'      
    );

    var $id;
    var $dia_absoluto;
    var $dia_semana;
    var $hora;
    var $id_recoleccion;
    var $id_envio;
    var $baja_logica;
    
    function toString() {
    	if(!empty($this->dia_absoluto)) {
    		// Dia Absoluto	
    		return "Todos los ".$this->dia_absoluto;
    	}
    	else {
    		if(!empty($this->dia_semana)) {
    			// Semanalmente
    			$nombre_dia = "";
    			switch($this->dia_semana) {
    				case CONST_DIA_LUNES: $nombre_dia = PropertiesHelper::GetKey(dias.lunes); break;
    				case CONST_DIA_MARTES: $nombre_dia = PropertiesHelper::GetKey(dias.martes); break;
    				case CONST_DIA_MIERCOLES: $nombre_dia = PropertiesHelper::GetKey(dias.miercoles); break;
    				case CONST_DIA_JUEVES: $nombre_dia = PropertiesHelper::GetKey(dias.jueves); break;
    				case CONST_DIA_VIERNES: $nombre_dia = PropertiesHelper::GetKey(dias.viernes); break;
    				case CONST_DIA_SABADO: $nombre_dia = PropertiesHelper::GetKey(dias.sabado); break;
    				case CONST_DIA_DOMINGO: $nombre_dia = PropertiesHelper::GetKey(dias.domingo); break;
    			}
    			return "Todos los ".$nombre_dia." a las ".$this->hora;
    			
    		}
    		else {
    			// Diariamente
    			return "Todos los d&iacute;as a las ".$this->hora;
    		}
    	}
    }
}

?>