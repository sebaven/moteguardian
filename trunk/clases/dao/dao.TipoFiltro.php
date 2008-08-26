<?php
include_once BASE_DIR ."clases/negocio/clase.TipoFiltro.php";
include_once BASE_DIR ."clases/dao/dao.NodoPlantilla.php";
include_once BASE_DIR ."clases/dao/dao.TipoFiltro.php";

class TipoFiltroDAO extends AbstractDAO
{
	function getEntity() 
	{
		return new TipoFiltro();
	}
	
	function getAllFiltrosConTipo($idPlantilla)
	{	
		// Obtengo todos los daos que voy a usar	
		$nodoPlantillaDAO = new NodoPlantillaDAO();
		$tipoFiltroDAO = new TipoFiltroDAO();
		
		$nodoPlantillaAnterior = null;
		$ordenFiltro = 1;		
		$nodoPlantillaIteracion = $nodoPlantillaDAO->getByPlantillaYAnterior($idPlantilla, $nodoPlantillaAnterior->id);
		
		while($nodoPlantillaIteracion != null)
		{
			$tipoFiltro = $tipoFiltroDAO->filterByField(id, $nodoPlantillaIteracion->id_tipo_filtro);
			$listado['js'] = $tipoFiltro->js;
			$listado['ordenFiltro'] = $ordenFiltro;
			$ordenFiltro++;
			$listado['nombreTipoFiltro'] = $tipoFiltro->nombre;
			$listado['idnodoPlantilla'] = $idNodoPlantillaIteracion;			 	
		}
	}	
}

?>