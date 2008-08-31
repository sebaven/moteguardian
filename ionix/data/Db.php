<?
/**
 * Clase gen�rica para el manejo de las conexiones a la base de datos
 * 
 * ----------------------------------------
 *      iOnix - PHP Framework - 2006
 * ----------------------------------------
 * Jorge Barbosa      <jmbarbosa@gmail.com>
 * Alejandro Molinari <amolinari@gmail.com>
 * ----------------------------------------
 * 
 * @package data
 * @version 1.2
 * @author jbarbosa
 * @author amolinari
 */ 
class Db
{	
	/**
	 * �ltima sentencia SQL usada
	 * @access private
	 * @var string
	 */
	var $query = "";
	
	/**
	 * Id generado en el �ltimo INSERT
	 * @access private
	 * @var int
	 */ 
	var $ultimo_id = 0;
	
	/**
	 * Puntero a la conexi�n de la base
	 * @access private
	 * @var object
	 */ 
	var $enlace = 0;
	
	/**
	 * Puntero al �ltimo resultado generado
	 * @access private
	 * @var object
	 */ 
	var $resultado = 0;
	
	/**
	 * Nombre del servidor
	 * @access private
	 * @var string
	 */ 
	var $servidor = "";

	/**
	 * Nombre de usuario de la base de datos
	 * @access private
	 * @var string
	 */ 
	var $usuario = "";

	/**
	 * Password
	 * @access private
	 * @var string
	 */
	var $clave = "";

	/**
	 * Nombre de la base de datos
	 * @access private
	 * @var string
	 */ 
	var $base = "";

	/**
	 * Nombre del motor de base de datos
	 * @access public
	 * @var string
	 */ 
	var $motor;

	/**
	 * Indica si se van a generar errores de PHP a nivel de usuario cuando no se puede establecer una
	 * conexi�n o cuando hay problemas al ejecutar una consulta
	 * @access public
	 * @var boolean
	 */ 
	var $generar_errores = true;

	/**
	 * Si se especifican los par�metros establece la conexi�n con la base de datos
	 * 
	 * @access public
	 * @param $servidor (string) nombre de host
	 * @param $user (string) nombre de usuario
	 * @param $password (string) clave
	 * @param $base (string) nombre de la base de datos
	 * @return (boolean) devuelve el identificador de conexi�n, o FALSE si no se puede conectar
	 */ 
	function Db($servidor = "", $user = "", $password = "", $base = "")
	{
		if($servidor && $user && $password && $base)
				return $this->conectar($servidor, $user, $password, $base);
		
		// No se especificaron todos los par�metros para establecer la conexi�n
		return false;
	}

	/**
	 * Establece la conexi�n con la base de datos. Este m�todo debe sobreescribirse para cada motor
	 * de base de datos
	 * 
	 * @access public
	 * @param $servidor (string) nombre de host
	 * @param $user (string) nombre de usuario
	 * @param $password (string) clave
	 * @param $base (string) nombre de la base de datos
	 * @param $flag (boolean) flag adicional para el motor
	 * @param $persistente (boolean) indica si es un tipo de conexi�n persistente
	 * @return (object) devuelve el identificador de conexi�n, o FALSE si no se puede conectar
	 */ 
	function conectar($servidor = "", $usuario = "", $clave = "", $base = "", $flag = true, $persistente = false)
	{
		// Guardar los par�metros de la base de datos
		$servidor = ($servidor) ? $servidor : '';
		$usuario = ($usuario) ? $usuario : '';
		$clave = ($clave) ? $clave : '';
		$base = ($base) ? $base : '';
		
		return true;
	}

	/**
	 * Cierra la conexi�n con la base de datos. Debe sobreescribirse para cada motor de base de datos
	 * 
	 * @access public
	 * @return (bool) TRUE si se desconect� correctamente, FALSE si hay error
	 */ 
	function desconectar()
	{
		return true;
	}
		
	/**
	 * Devuelve el conjunto de registros de un SELECT
	 * 
	 * @access public
	 * @param $sql (string) sentencia SQL a ejecutar
	 * @return (object) result set con el resultado de la consulta
	 */ 
	function leer($sql)
	{
		// Guardar la �ltima consulta ejecutada
		$this->query = $sql;				
		$this->resultado = $this->query($sql, $this->enlace);

		if (! $this->resultado)
			return $this->generar_error($sql);

		return $this->resultado;
	}

	/**
	 * Devuelve un array con todos los registros resultantes de la consulta con la sentencia $sql
	 * Si no hay registros en la base devuelve un array vacio
	 * 
	 * @access public
	 * @param $sql (string) sentencia SQL
	 * @return (array) contiene los registros obtenidos de la consulta, indexado por nro. y por clave
	 */
	function leer_todos($sql)
	{
        $ret = array();
        $q = $this->leer($sql);
        $r = 0;
        while($r = $this->reg_array($q))
        {
	        $r++;
	        $ret[] = $r;
        }

        return $ret;
	}
	
	/**
	 * Ejecuta una sentencia SQL que no devuelve resultados, tales com INSERT, UPDATE o DELETE
	 * 
	 * @access public
	 * @param $sql (string) sentencia SQL a ejecutar
	 * @return (bool) TRUE si se ejecut� correctamente, FALSE en caso contrario
	 */ 
	function ejecutar($sql)
	{
		// Guadar la �ltima consulta ejecutada
		$this->query = $sql;
		
		$q = $this->query($sql, $this->enlace);

		if (! $q)
			return $this->generar_error($sql);
	
		// Guadar el id devuelto por la consulta
		$this->ultimo_id  = $this->insert_id();
		
		return true;
	}
	
	/**
	 * Devuelve el id devuelto por una consulta INSERT. Este m�todo hay que sobreescribirlo 
	 * para cada motor de base de datos
	 * 
	 * @access public
	 * @param $tabla (string) nombre de la tabla
	 * @param $campo (string) nombre del campo
	 * @return (int) id devuelto por la consulta INSERT
	 */ 
	function insert_id($tabla = '', $campo = '')
	{
		return 0;
	}

	/**
	 * Devuelve la cantidad de filas de un resultado de query
	 * 
	 * @access public
	 * @param (object) result set
	 * @return (int) cantidad de filas en el resulta set
	 */ 
	function numero_filas($rs)
	{}
	
	/**
	 * Devuelve un array con los datos del result set especificado
	 * 
	 * @access public
	 * @param $rs result set del cual se obtienen los datos
	 * @return (array) array con los datos del result set especificado
	 */ 
	function reg_array($rs = 0)
	{
		if ($rs == 0)
			$rs = $this->resultado;
		
		if ($rs)
			return $this->fetch_array($rs);
		else
			return 0;
	}

	/**
     * Convierte el result set especificado en un array
     * 
	 * @access private
	 * @param $rs (object) result set
	 * @return (array) contiene los registros obtenidos de la consulta
	 * 
	 * Agregado por pfagalde. No estaba en la clase de la que heredan y se estaba llamando
	 * a "this"...esta bien?
	 * 
	 */ 
	function fetch_array($rs)
	{
    	return null;
    }
    
	/**
	 * Inicia una transacci�n
	 * 
	 * @access public
	 * @return void
	 */ 
	function begin()
	{
		// Sobreescribir de acuerdo al motor
	}

	/**
	 * Hace un rollback de la transacci�n actual
	 * 
	 * @access public
	 * @return void
	 */ 
	function rollback()
	{	
		// Sobreescribir de acuerdo al motor
	}
	
	/**
	 * Hace un commit de la transacci�n actual
	 * 
	 * @access public
	 * @return void
	 */ 
	function commit()
	{	
		// Sobreescribir de acuerdo al motor
	}

	/**
	 * Ejecuta la consulta de acuerdo al motor de base datos. Debe sobreescribirse
	 * 
	 * @access protected
	 * @param $sql (string) sentencia SQL a ejecutar
	 * @return (bool) TRUE si se ejecut� correctamente, FALSE en caso contrario
	 */ 
	function query($sql)
	{}

	/**
	 * Si se especific� que se generen errores, por medio de $this->generar_errores, genera un error
	 * con la descripci�n y el tipo indicado.<br/>
	 * (Los tipos de codigo pueden ser:  E_USER_ERROR, E_USER_WARNING o E_USER_NOTICE)
	 * 
	 * @access private
	 * @param $desc (string) descripci�n del error
	 * @param $codigo (int) tipo de error que se va a generar
	 * @return (bool) FALSE
	 */ 
	function generar_error($desc, $codigo = E_USER_ERROR)
	{
		if ($this->generar_errores)
		{
			// Generar el error 
			trigger_error(htmlentities($desc), $codigo);
		}
		
		return false;
	}

	/**
	 * Libera los recursos utilizados para un resultado 
	 * 
	 * @access private
	 * @param (object) result set
	 * @return void
	 */ 
	function liberar($rs = 0)
	{
		if ($rs)
		{
			$this->free_result($rs);
		}
		else if ($this->resultado)
		{
			$this->free_result($this->resultado);
		}
	}

	/**
	 * Devuelve un array con el resultado de la consulta
	 * 
	 * @access private
	 * @param $sql (string) sentencia SQL
	 * @return (array) contiene los registros obtenidos de la consulta
	 */ 
	function leer_array($sql)
	{
		$r = $this->leer($sql);
		$res = $this->fetch_array($r, $this->enlace);

		return $res;
	}
}
?>