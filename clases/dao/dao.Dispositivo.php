<?php
include_once BASE_DIR ."clases/negocio/clase.Dispositivo.php";

class DispositivoDAO extends AbstractDAO
{
	function getEntity()
	{
		return new Dispositivo();
	}
    
    function getSql($values = '')
    {
        $w = array();
        
        if($values['descripcion']){
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
        }
                      
        $w[] = "d.baja_logica = '".FALSE_."'";
        $w[] = "s.baja_logica = '".FALSE_."'";
            
        $sql  = "SELECT d.id, ";
        $sql .= "       d.codigo, ";
        $sql .= "       d.tipo,";
        $sql .= "       d.descripcion,";
        $sql .= "       s.descripcion as sala,";
        $sql .= "       d.estado ";
        $sql .= "FROM dispositivo d ";
        $sql .= 	"INNER JOIN sala s ON d.id_sala = s.id ";

        if ($w)
        {
            $sql .= "WHERE " . implode(' AND ', $w) . " ";
        }

        return $sql;
    }
}
?>