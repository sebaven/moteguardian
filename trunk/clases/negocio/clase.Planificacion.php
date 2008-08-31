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
    	'fecha_vigencia' => 'date',    
		'dia_semana' => 'varchar',
		'hora' => 'varchar',
		'id_recoleccion' => 'int',
		'id_envio' => 'int',
    	'baja_logica' => 'boolean'      
    );

    var $id;
    var $dia_absoluto;
    var $fecha_vigencia;
    var $dia_semana;
    var $hora;
    var $id_recoleccion;
    var $id_envio;
    var $baja_logica;
    
    function toString() {
    	if(!empty($this->dia_absoluto)) {
    		// Dia Absoluto	
    		return "El ".$this->dia_absoluto." a las ".$this->hora;
    	}
    	else {
    		if(!empty($this->dia_semana)) {
    			// Semanalmente   			
    			return "Todos los ".$this->dia_semana." a las ".$this->hora;
    			
    		}
    		else {
    			// Diariamente
    			return "Todos los días a las ".$this->hora;
    		}
    	}
    }
}

?>