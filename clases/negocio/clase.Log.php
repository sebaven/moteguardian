<?
class Log extends AbstractEntity
{
    /// Nombre de la tabla sobre a la cual accede la clase
    /// @protected
    /// @var string
    var $_tablename = 'log';
	
    /// Nombre de los campos, menos el campo id
    /// @protected
    /// @var array
    var $_fields = array
    (
      'id_usuario' => 'int',
      'id_accion' => 'int',
      'parametro' => 'varchar',
	  'fecha' => 'date',
	  'hora' => 'date',
      'baja_logica' => 'int'
    );

    var $id;
    var $id_usuario;
    var $id_accion;
    var $parametro;
    var $fecha;
	var $hora;
    var $baja_logica;   
	
	/// Devuelve true si el caracter pasado por parametro es un numero (0 - 9)
	/// o false en caso contrario
	/// @param $caracter es un caracter
	/// @return (bool)
	/// @private
	function verificar_numerico($caracter){

		return (($caracter >= "0") && ($caracter < "9"));
	}

	/// Devuelve una fecha con el formato YYYY-mm-dd en el caso de que la fecha
	/// pasada por parametro sea valida. Si la fecha por parametro no es valida
	/// entonces devuelve un string vacio
	/// @param $fecha es una fecha con formato dd-mm-YYYY o dd/mm/YYYY
	/// @return (string)
	/// @private
	function convert_date_2_YYYYMMDD($fecha){
		
		// Se divide la fecha ingresada segun el separador "-"
		$fecha_array = explode("-", $fecha);

		// En el caso de que el separador no era "-" entonces el fecha_array
		// tendra solo un campo. O sea el separador es "/"
		if ($fecha_array[1] == ""){

			// Si el separador es "/"
			$fecha_array = explode("/", $fecha);
		}
		
		$fecha_convertida = $fecha_array[2] . "-" . $fecha_array[1] . "-" . $fecha_array[0];

		// Verifico que lo devuelto sea una fecha con el formato YYYY-mm-dd
		if (($this->verificar_numerico($fecha_convertida[0])) &&
			($this->verificar_numerico($fecha_convertida[1])) &&
			($this->verificar_numerico($fecha_convertida[2])) &&
			($this->verificar_numerico($fecha_convertida[3])) &&
			($fecha_convertida[4] == "-") &&
			($this->verificar_numerico($fecha_convertida[5])) &&
			($this->verificar_numerico($fecha_convertida[6])) &&
			($fecha_convertida[7] == "-") &&
			($this->verificar_numerico($fecha_convertida[8])) &&
			($this->verificar_numerico($fecha_convertida[9]))){

				// Si la fecha es valida la retorno
				return $fecha_convertida;
		}else{
			
			// Si no valida la fecha entonces retorno un string vacio
			return "";
		}
	}

	/// Devuelve una sentencia SQL para realizar la busqueda de registros en el log de acuerdo a los 
	/// filtros especificados
	/// @param $values (array asociativo) contiene como clave los campos a filtrar con los respectivos valores
	/// @return (string) sentencia SQL
	function getSql($values = '')
	{
		$w = array();
		
		if ($values['usuario'])
			$w[] = "id_usuario = '" . $values['usuario'] . "'";
			
		// TODO: convertir fechas a YYYY-MM-DD
		$fecha_desde_convertida = $this->convert_date_2_YYYYMMDD($values['fecha_desde']);
		$fecha_hasta_convertida = $this->convert_date_2_YYYYMMDD($values['fecha_hasta']);

		if ($fecha_desde_convertida != "")
			$w[] = "fecha >= '" . $fecha_desde_convertida . "'";

		if ($fecha_hasta_convertida != "")
			$w[] = "fecha <= '" . $fecha_hasta_convertida . "'";

		if ($values['acciones'])
			$w[] = "id_accion = '" . $values['acciones'] . "'";

		if ($values['torneo'])
			$w[] = "torneo = '" . $values['torneo'] . "'";
			
		if ($values['rueda'])
			$w[] = "rueda = '" . $values['rueda'] . "'";

		if ($values['numero_fecha'])
			$w[] = "numero_fecha = '" . $values['numero_fecha'] . "'";
			
		if ($values['partido'])
			$w[] = "partido = '" . $values['partido'] . "'";

		$sql  = "SELECT l.id, ";
		$sql .=	"		id_usuario, ";
		$sql .=	"		usuario, ";
		$sql .= "		DATE_FORMAT(fecha, '%d/%m/%Y') AS fecha_dmy, ";
		$sql .= "		hora, ";
		$sql .=	"		parametro AS descripcion,  ";
		$sql .= "		a.descripcion AS accion ";
		$sql .=	"FROM log l LEFT JOIN accion a ON a.id = l.id_accion LEFT JOIN usuario u ON u.id = l.id_usuario ";

		if ($w)
		{
			$sql .= "WHERE " . implode(' AND ', $w) . " ";
		}

		return $sql;
	}
				
	/// Devuelve un string con la fecha de �ltimo acceso al sistema del usuario especificado
	/// @public
	/// @return (string) fecha de �ltimo acceso al sistema
	function getUltimoAccesoUsuario($id_usuario)
	{
		$sql  = "SELECT to_char(date_part('day', fecha), 'FM00') || '/' || to_char(date_part('month', fecha), 'FM00') || '/' || date_part('year',fecha) || ' ' || hora AS fecha_log ";
		$sql .= "FROM $this->_tablename ";
		$sql .= "WHERE id_usuario = '$id_usuario' AND id_accion = 1 ";
		$sql .= "ORDER BY fecha DESC ";
		$sql .= "LIMIT 2 ";

		$rs = $this->_db->leer($sql);
		$v = $this->_rs2vector($rs, 'fecha_log');

		return $v[1];
	}
}
?>