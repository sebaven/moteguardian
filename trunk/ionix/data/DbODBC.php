<?
/**
 * Clase para el manejo de la conexi�n a un ODBC
 * 
 * 
 * @version 1.0
 * @package data
 * @author pfagalde
 */
class DbODBC extends Db
{
	/**
	 * Constructor de la clase
	 * 
	 * @access public
	 */
	function DbODBC()
	{}

	/**
	 *  Establece la conexi�n con la base de datos
	 * 
	 *  @access public	
	 *  @param $servidor (string) nombre de host
	 *  @param $user (string) nombre de usuario
	 *  @param $password (string) clave
	 *  @param $base (string) nombre de la base de datos
	 *  @param $flag (boolean) flag adicional para el motor
	 *  @param $persistente (boolean) indica si es un tipo de conexi�n persistente
	 *  @return (object) devuelve el identificador de conexi�n, o FALSE si no se puede conectar
	 */ 
	function conectar($servidor = "", $usuario = "", $clave = "", $base = "", $flag = true, $persistente = false)
	{
		parent::conectar($servidor, $usuario, $clase, $base, $flag, $persistente);

		$this->enlace = odbc_connect ($base, $usuario, $clave);

		return $this->enlace;	
	}

	/**
	 * Cierra la conexi�n con la base de datos
	 * 
	 * @access public
	 * @return (bool) TRUE si se desconect� correctamente, FALSE si hay error
	 */
	function desconectar()
	{
		return odbc_close($enlace);
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
		die("Operacion no soportada");
	}

	/**
	 * Devuelve la cantidad de filas de un resultado de query
	 * 
	 * @access public
	 * @param (object) result set
	 * @return (int) cantidad de filas en el resulta set
	 */
	function numero_filas($rs)
	{
		// Fix para odbc_num_rows que siempre devuelve -1 para MS Access
	    $count = 0;
	    while($temp = odbc_fetch_into($rs, $counter)){
    	    $count++;
    	}    
    	
    	// Reset cursor position
    	odbc_fetch_row($rs, 0);
    	
    	return $count;
    }

    /**
     * Ejecuta la consulta de acuerdo al motor de base datos
     * 
     * @access protected
     * @param $sql (string) sentencia SQL a ejecutar
     * @return (bool) TRUE si se ejecut� correctamente, FALSE en caso contrario
     */
    function query($sql)
	{
		return odbc_exec($this->enlace,$sql);
	}
	
	/**
	 * Libera los recursos utilizados para un resultado 
	 * 
	 * @access private
	 * @param (object) result set
	 * @return void
	 */
	function free_result($rs)
	{
		odbc_free_result($rs);
	}

	/**
	 * Convierte el result set especificado en un array
	 * 
	 * @access private
	 * @param $rs (object) result set
	 * @return (array) contiene los registros obtenidos de la consulta
	 */
    function fetch_array($rs)
	{
    	return odbc_fetch_array($rs);
    }
    
	/**
     * Inicia una transacci�n
     * 
	 * @access public
	 * @return void
	 */ 
	function begin()
	{
		$this->query('BEGIN;');
	}

	/**
	 * Hace un rollback de la transacci�n actual
	 * 
	 * @access public
	 * @return void
	 */ 
	function rollback()
	{	
		$this->query('ROLLBACK;');
	}
	
	/**
	 * Hace un commit de la transacci�n actual
	 * 
	 * @access public
	 * @return void
	 */ 
	function commit()
	{	
		$this->query('COMMIT;');
	}
}
?>