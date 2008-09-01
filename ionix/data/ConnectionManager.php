<?
/**
 * Esta clase provee m�todos est�ticos para acceder a conexiones a la base de datos registradas en el archivo de configurci�n XML. 
 * Permite obtener las conexiones a los diferentes motores registrados.
 *
 * ----------------------------------------
 *      iOnix - PHP Framework - 2006
 * ----------------------------------------
 * Jorge Barbosa      <jmbarbosa@gmail.com>
 * Alejandro Molinari <amolinari@gmail.com>
 * ----------------------------------------
 * 
 * @version 1.0
 * @package data
 * @author jbarbosa
 * @author amolinari
 */ 
class ConnectionManager
{
	/**
	 * Devuelve una objeto del tipo de base de datos correcto de acuerdo a los par�metros de configuraci�n
	 * de la conexi�n especificada. Si el nombre es vac�o se devuelve la conexi�n por defecto.
	 * 
	 * @access public
	 * @static
	 * @param $name (string) nombre de la conexi�n a la base de datos
	 * @return (object) objeto con la referencia a la a base de datos especificada
	 */ 
	static function getConnection($name = '')
	{
		static $connections = array();
		
		if (empty($connections[$name]))
		{
			$params = ConnectionManager::getParams($name);
			
			// Buscar la implementaci�n seg�n el tipo de motor
			if ($params['driver'] == 'postgres')
			{	
				$db = new DbPg();
			}			
			else if ($params['driver'] == 'mysql')
			{
				$db = new DbMySql();
			}
			else if ($params['driver'] == 'odbc')
			{
				$db = new DbODBC();
			}
			
			if ($params)
			{
				$db->conectar($params['hostname'], $params['username'], $params['password'], $params['base']);
				$connections[$name] = $db;
			}
		}
		
		return $connections[$name];				
	}

	/**
	 * Devuelve un array con la configuraci�n de la conexi�n especificada. 
	 * Si el nombre es vac�o se devuelven los par�metros por defecto
	 * 
	 * @access private
	 * @static
	 * @param $name (string) nombre de la conexi�n a la base de datos
	 * @return (array) contiene cada uno de los par�metros de configuraci�n de la base de datos especificada
	 */ 
	static function getParams($name)
	{
		static $datasources = array();
		
		// Cargar los datasources del archivo XML
		if (! $datasources)
		{		
			$datasources = ConnectionManager::getDatasourcesFromXml();			
		}
		
		// Si est� vac�o buscar la primera conexi�n
		if (empty($name))
		{
			foreach ($datasources as $datasource)
			{
				$name = $datasource['name'];
				break;
			}
		}
		
		return $datasources[$name];
	}
	
	/**
	 * Carga todas las conexiones registradas en el archivo de configurci�n XML
	 * @access private
	 * @static
	 * @return void
	 */ 
	static function getDatasourcesFromXml()
	{
		$_datasources = array();
		
		// Abrir el archivo XML y obtener el root
		$dom = new DomDocument();
		$dom->load(CONFIG . DATASOURCES_FILE);

		// Procesar cada uno de los datasources
		$datasources = $dom->getElementsByTagname("datasource");		
		foreach ($datasources as $datasource)
		{
			// Name
			$name = $datasource->getElementsByTagname('name')->item(0)->firstChild->data;

			// Base
			$base = $datasource->getElementsByTagname('base')->item(0)->firstChild->data;

			// Hostname
			$hostname = $datasource->getElementsByTagname('hostname')->item(0)->firstChild;
			if ($hostname != null) $hostname = $hostname->data;

			// Driver
			$driver = $datasource->getElementsByTagname('driver')->item(0)->firstChild->data;

			// Username
			$username = $datasource->getElementsByTagname('username')->item(0)->firstChild;
			if ($username != null) $username = $username->data;

			// Password
			$password = $datasource->getElementsByTagname('password')->item(0)->firstChild;
			if ($password != null) $password = $password->data;

			// Port
			$port = $datasource->getElementsByTagname('port')->item(0)->firstChild;
			if ($port != null) $port = $port->data;

			// Indexar los datasources
			$_datasources[$name] = array('name' => $name, 
										 'base' => $base, 
										 'hostname' => $hostname, 
										 'driver' => $driver, 
										 'username' => $username,
										 'password' => $password,
										 'port' => $port);	
		}
		
		return $_datasources;
	}
}
?>