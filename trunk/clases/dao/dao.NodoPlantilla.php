<?php
include_once BASE_DIR ."clases/negocio/clase.NodoPlantilla.php";

class NodoPlantillaDAO extends AbstractDAO
{
	function getEntity()
	{
		return new NodoPlantilla();
	}
	
	function getNodoSiguiente($idNodoPlantilla)
	{
		$resultados = $this->filterByField("id_nodo_plantilla_anterior", $idNodoPlantilla);
		// FIXME: Esto anda aunque no traiga nada??? NO	 
		return $resultados[0];		
	}
	
	function getPrimeroEnPlantilla($idPlantilla) {
		$sql = "SELECT * FROM nodo_plantilla ";
		$sql.= "WHERE baja_logica = '".FALSE_."' ";
		$sql.= "AND id_plantilla = '".$idPlantilla."' ";
		$sql.= "AND id_nodo_plantilla_anterior IS NULL ";
		
		// *** Realizar la consutla *** //
		$res = $this->getEntity()->_db->leer($sql);
		
		return $this->_rs2Entity($res);
	}
	
/**
	 * Devuelve un array con todos los objetos que cumplan con la condici�n que el campo $field contenga el valor
	 * dado en $value
	 * 
	 * @access public
	 * @param $field (string) nombre del campo a filtrar
	 * @param $value (string) valor que debe tener el campo
	 * @return array con los objetos que cumplen la condici�n
	 */ 
	function filterByField($field, $value)
	{
		// *** Armar la consulta *** //
		$sql  = " SELECT *";
		$sql .= " FROM nodo_plantilla ";
		$sql .= " WHERE $field = '".addslashes($value)."'";
		$sql .= " AND baja_logica ='".FALSE_."'";
				
		// *** Realizar la consutla *** //
		$res = $this->getEntity()->_db->leer($sql);
		
		return $this->_rs2Collection($res);
	}
	
	function validarNombre($nombre, $id_plantilla) {
		// *** Armar la consulta *** //
		$sql  = " SELECT *";
		$sql .= " FROM nodo_plantilla ";
		$sql .= " WHERE nombre = '".addslashes($nombre)."'";
		$sql .= " AND baja_logica ='".FALSE_."' ";
		$sql .= " AND id_plantilla ='".addslashes($id_plantilla)."'";
		
		// *** Realizar la consutla *** //
		$res = $this->getEntity()->_db->leer($sql);
		
		return $this->_rs2Collection($res);
	}
	
	function getByIdPlantilla($id_plantilla){
		$entity = $this->getEntity();		
		
		$sql  = "SELECT ";
		$sql .= 	"nombre ";
		$sql .= "FROM nodo_plantilla ";
		$sql .= "WHERE baja_logica ='".FALSE_."' ";
		$sql .= 	"AND id_plantilla ='".addslashes($id_plantilla)."'";
		
		$res = $entity->_db->leer($sql);
		return $this->_rs2Collection($res);
	}
}
?>