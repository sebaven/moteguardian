<?php
include_once BASE_DIR ."clases/negocio/clase.LogAlarma.php";

class LogAlarmaDAO extends AbstractDAO
{
	function getEntity()
	{
		return new LogAlarma();
	}
    
    function getAlarmasReales($values = '')
    {
        $w = array();
                       
        if($values['desde']){
            $w[] = "l.timestamp_inicio '".$values['desde']."'";            
        }
            
        if($values['hasta']){
            $w[] = "l.timestamp_fin <= '".$values['hasta']."'"; 
        }
        
        $w[] = "l.es_falsa='".FALSE_."'";
                      
        $sql = "SELECT count(*) as reales FROM log_alarma l ";
        if ($w)
        {
            $sql .= "WHERE " . implode(' AND ', $w) . " ";
        }

        $db = $this->getEntity()->_db;
        $resource = $db->leer($sql);
        $result = $this->_rs2Collection($resource);
        return $result[0]->reales;
    }
    
    function getFalsasAlarmas($values = '')
    {
        $w = array();
                       
        if($values['desde']){
            $w[] = "l.timestamp_inicio '".$values['desde']."'";            
        }
            
        if($values['hasta']){
            $w[] = "l.timestamp_fin <= '".$values['hasta']."'"; 
        }
        
        $w[] = "es_falsa='".TRUE_."'";
                      
        $sql = "SELECT count(*) as falsas FROM log_alarma l ";
        if ($w)
        {
            $sql .= "WHERE " . implode(' AND ', $w) . " ";
        }

        $db = $this->getEntity()->_db;
        $resource = $db->leer($sql);
        $result = $this->_rs2Collection($resource);
        return $result[0]->falsas;
    }
    
    function getSql($values = '')
    {
        $w = array();
                       
        if($values['desde']){
            $w[] = "l.timestamp_inicio >= '$values[desde]'";            
        }
            
        if($values['hasta']){
            $w[] = "l.timestamp_fin <= '$values[hasta]'"; 
        }
        
        $sql = "SELECT d.codigo as codigo, l.timestamp_inicio as inicio, l.timestamp_fin as fin, l.es_falsa as falsa FROM log_alarma l INNER JOIN dispositivo d ON d.id = l.id_dispositivo_disparador ";
        if ($w)
        {
            $sql .= "WHERE " . implode(' AND ', $w) . " ";
        }

        return $sql;
    }
    
    function getSalaAlarmaIniciada()
    {    	
    	$sql = 
				"SELECT s.id as id_sala ".
				"FROM log_alarma la ".
					"INNER JOIN dispositivo d ON la.id_dispositivo_disparador = d.id ".
					"INNER JOIN sala s ON s.id = d.id_sala ".
				"WHERE la.timestamp_fin IS NULL ".
					"AND la.baja_logica = '".FALSE_."' ";
    	
    	$alarmas = $this->_rs2Collection($this->getEntity()->_db->leer($sql));
    	
    	return $alarmas[0]->id_sala;
    }
    
	
    function marcarAlarmasComoFalsas()
    {    	
		$sql =
				"UPDATE log_alarma ".
				"SET timestamp_fin = NOW( ), ".
					"es_falsa = '".TRUE_."' ".
				"WHERE timestamp_fin IS NULL ";					
    	
    	$this->getEntity()->_db->leer($sql);
    	
    	$sql =
				"UPDATE log_mota ".
				"SET timestamp_fin = NOW( ) ".					
				"WHERE timestamp_fin IS NULL ";
    	
    	$this->getEntity()->_db->leer($sql);

    	$sql =
				"UPDATE dispositivo ".
				"SET estado = '".CONST_EN_USO."' ".    						
				"WHERE estado = '".CONST_EN_ALARMA."' ";
    	
    	$this->getEntity()->_db->leer($sql);
	}
	
    function marcarAlarmasComoReales()
    {       	
    	$sql =
				"UPDATE log_mota ".
				"SET timestamp_fin = NOW( ) ".					
				"WHERE timestamp_fin IS NULL ";
    	
    	$this->getEntity()->_db->leer($sql);
    	
		$sql =
				"UPDATE log_alarma ".
				"SET timestamp_fin = NOW( ), ".
					"es_falsa = '".FALSE_."' ".
				"WHERE timestamp_fin IS NULL ";					
    		
    	
    	$this->getEntity()->_db->leer($sql);

    	$sql =
				"UPDATE dispositivo ".
				"SET estado = '".CONST_EN_USO."' ".    						
				"WHERE estado = '".CONST_EN_ALARMA."' ";
    	
    	$this->getEntity()->_db->leer($sql);   	
	}
	
}

?>