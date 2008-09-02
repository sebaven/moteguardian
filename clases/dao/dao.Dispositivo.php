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
        
/*        if ($values['usuario'])
            $w[] = "usuario LIKE '%" . addslashes($values['usuario']) . "%'";
            
        if ($values['nombre'])
            $w[] = "nombre = '" . addslashes($values['nombre']) . "'";
            
        if ($values['apellido'])
            $w[] = "apellido = '" . addslashes($values['apellido']) . "'";
            
        if ($values['email'])
            $w[] = "email = '" . addslashes($values['email']) . "'";
            
        if ($values['otros_mails'])
            $w[] = "otros_mails = '" . addslashes($values['otros_mails']) . "'";
            
        if ($values['telefono1'])
            $w[] = "telefono1 = '" . addslashes($values['telefono1']) . "'";
        
        if ($values['telefono2'])
            $w[] = "telefono2 = '" . addslashes($values['telefono2']) . "'";
            
        if ($values['id_rol'])
            $w[] = "id_rol = '" . addslashes($values['id_rol']) . "'";
  */                  
        $w[] = "d.baja_logica = '".FALSE_."'";
            
        $sql  = "SELECT d.id, ";
        $sql .= "       d.codigo, ";
        $sql .= "       d.tipo,";
        $sql .= "       d.descripcion,";
        $sql .= "       d.id_sala as sala,";
        $sql .= "       d.estado ";
        $sql .= "FROM dispositivo d ";
        //$sql .= "INNER JOIN rol ON rol.id = u.id_rol ";        

        if ($w)
        {
            $sql .= "WHERE " . implode(' AND ', $w) . " ";
        }

        return $sql;
    }
}
?>