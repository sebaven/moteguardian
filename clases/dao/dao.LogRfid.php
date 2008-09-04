<?php
include_once BASE_DIR ."clases/negocio/clase.LogRfid.php";

class LogRfidDAO extends AbstractDAO
{
	function getEntity()
	{
		return new LogRfid();
	}
    
    function getSqlRondasRealizadas($values = '')
    {
        $w = array();
                       
        if($values['nombre']){
            $w[] = "g.nombre LIKE '%".addEscapeosParaLike($values['nombre'])."%'";            
        }
            
        if($values['codigo_tarjeta']){
            $w[] = "g.codigo_tarjeta LIKE '%".addEscapeosParaLike($values['codigo_tarjeta'])."%'"; 
        }
        /*if($values['descripcion']){
            $w[] = "descripcion LIKE '%".addEscapeosParaLike($values['usuario'])."%'";            
        }
            
        if($values['id_sala']){
            $w[] = "id_sala = '".addslashes($values['id_sala'])."'";
        }
            
        if($values['tipo']){
            $w[] = "tipo = '".addslashes($values['tipo'])."'";
        }
        
        if($values['estado']){
            $w[] = "estado LIKE '%".addEscapeosParaLike($values['estado'])."%'";
        }
            
        if($values['codigo']){
            $w[] = "codigo LIKE '%".addEscapeosParaLike($values['codigo'])."%'";
        } */
                      
        $w[] = "l.baja_logica = '".FALSE_."'";                                              
        $w[] = "d.baja_logica = '".FALSE_."'";
        $w[] = "s.baja_logica = '".FALSE_."'";
        $w[] = "g.baja_logica = '".FALSE_."'";

        $sql  = "SELECT l.id, ";
        $sql .= "       l.timestamp_inicio as inicio, ";
        $sql .= "       l.timestamp_fin as fin,";
        $sql .= "       l.codigo_tarjeta as tarjeta,";
        $sql .= "       g.nombre as nombre,";       
        $sql .= "       s.descripcion as sala ";
        $sql .= "FROM log_rfid l ";
        $sql .= "INNER JOIN guardia g ON g.codigo_tarjeta = l.codigo_tarjeta ";
        $sql .= "INNER JOIN dispositivo d ON d.id = l.id_rfid ";
        $sql .= "INNER JOIN sala s ON d.id_sala = s.id ";

        if ($w)
        {
            $sql .= "WHERE " . implode(' AND ', $w) . " ";
        }

        return $sql;
    }
}

?>