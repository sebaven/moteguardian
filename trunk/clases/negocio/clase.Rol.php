<?
/// @date 31/01/2008
/// @version 1.0
/// @author fzalazar

class Rol extends AbstractEntity
{
	/// Nombre de la tabla sobre a la cual accede la clase
	/// @protected
	/// @var string
	var $_tablename = 'rol';
	
	/// Nombre de los campos, menos el campo id
	/// @protected
	/// @var array
	var $_fields = array
	(
		'descripcion' => 'varchar',
        'baja_logica' => 'int'
	);

	var $id;
	var $descripcion;
    var $baja_logica;   
}
?>
