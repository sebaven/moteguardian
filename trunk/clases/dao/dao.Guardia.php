<?php
include_once BASE_DIR ."clases/negocio/clase.Guardia.php";

class GuardiaDAO extends AbstractDAO
{
	function getEntity()
	{
		return new Guardia();
	}
	
	function getSql($values = '')
    {
        $w = array();
        
        if($values['nombre']){
            $w[] = "g.nombre LIKE '%".addEscapeosParaLike($values['nombre'])."%'";        	
        }
            
        if($values['codigo_tarjeta']){
            $w[] = "g.codigo_tarjeta LIKE '%".addEscapeosParaLike($values['codigio_tarjeta'])."%'"; 
        }
            
        if($values['id_usuario']){
            $w[] = "g.id_usuario = '".addslashes($values['id_usuario'])."'";
        }
                                      
        $w[] = "g.baja_logica = '".FALSE_."'";
        $w[] = "u.baja_logica = '".FALSE_."'";
            
        $sql  = "SELECT g.id, ";
        $sql .= "       g.codigo_tarjeta, ";
        $sql .= "       g.nombre,";        
        $sql .= "       u.usuario as usuario ";        
        $sql .= "FROM guardia g ";
        $sql .= 	"INNER JOIN usuario u ON g.id_usuario = u.id ";

        if ($w)
        {
            $sql .= "WHERE " . implode(' AND ', $w) . " ";
        }

        return $sql;
    }
}

?>