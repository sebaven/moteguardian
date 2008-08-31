<?
/// Clase generica para validar los permisos del sistema, a traves de metodos ESTATICOS. 
/// Por defecto trabaja con la configuracion por XML.
/// @date 16/11/2006
/// @version 1.0
/// @author amolinari
class PermissionHelper
{
	/// Valida si un rol tiene acceso o no a una determinada accion
	/// @access public
	/// @return (bool) true si para la accion ingresada tiene acceso el rol solicitado
	function validateAccess($action_required, $rol_required)
	{
		// Abrir el archivo XML de permisos
		$dom = new DomDocument();
		$dom->load(realpath(".") . '/' . PERMISSION_FILE);

		// Levantar los tags de permisos y buscar la accion
		$permisos = $dom->getElementsByTagname("permiso");		
		foreach ($permisos as $permiso)
		{
			// Busco el nombre de la accion
			$accion = $permiso->getElementsByTagname("accion")->item(0)->firstChild->data;

			if ($accion == $action_required){
				// Busco los roles
				$roles = $permiso->getElementsByTagname("rol");

				// Para recorrer los roles que tienen permiso para la accion
				foreach ($roles as $rol)
				{
					if($rol->firstChild->data == $rol_required)
						return true;
				}
			}
		}
		// No se encontro el rol para la accion
		return false;		
	}
}
?>