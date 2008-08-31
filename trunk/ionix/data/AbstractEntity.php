<?
/**
 * Esta clase encapsula el acceso ala base de datos, usando la clase DB, y provee m�todos comunes de consulta y modificaci�n de la misma.
 * Consideraciones/Hip�tesis:<br/>
 *		-Todas las tablas tienen un identificador de registro (clave primaria) denominado 'id', de tipo entero y autonumerado.<br/>
 *
 * ----------------------------------------
 *      iOnix - PHP Framework - 2006
 * ----------------------------------------
 * Jorge Barbosa      <jmbarbosa@gmail.com>
 * Alejandro Molinari <amolinari@gmail.com>
 * ----------------------------------------
 * 
 * @version 1.5
 * @package data
 * @abstract 
 * @author jbarbosa
 */ 
class AbstractEntity
{
	/**
	 * Objeto para acceder a la base de datos
	 * @access protected
	 * @var object
	 */ 
	var $_db;

	/**
	 * Nombre de la tabla sobre a la cual accede la clase
	 * @access protected
	 * @var string
	 */ 
	var $_tablename = "base";
	
	/**
	 * Nombre de los campos, menos el campo id
	 * @access protected
	 * @var array	
	 */ 
	var $_fields = array
	(
		'var1' => 'varchar'
	);
		
	/**
	 * Nombre de la conexi�n a la base de datos. Si est� vac�o se toma la conexi�n por defecto
	 * @access protected
	 * @var string
	 */ 
	var $_connection = '';
	
	/**
	 * Contructor
	 * @access public
	 * @param $id (int) Identificador del registro que se va a cargar. Por defecto es vac�o.
	 * @return void
	 */ 
	function AbstractEntity($id = "")
	{
		// Obtener una conexi�n a la base
		$this->_db = ConnectionManager::getConnection($this->_connection);

		// Verificar parametro
		if (!empty($id))
		{
			$this->id = $id;
		}
		
		// Inicializar variables (vacio)
		foreach ($this->_fields as $key => $value)
		{
			eval('$this->$key = \'\';');
		}
		
		// Cargar 
		if (!empty($id))
		{
			$this->load($id);
		}
	}
	
	/**
	 * Carga todas las propiedades del objeto con los datos del registro con 'id' especificado.
	 * @access public
	 * @param $id (int) es el identificador del registro que se va a cargar. Si no se especifica se toma el valor de la propiedad 'id' de la clase.
	 * @return void
	 */ 
	function load($id = "")
	{
		// Verificar parametro
		if (!empty($id))
		{
			$this->id = $id;
		}			   
		
		// *** Realizar la consutlta *** //
		$reg = $this->getById();
		
		// *** Cargar las propiedades con los valores de la consulta *** //
		foreach($this->_fields as $key => $value)
		{
			   eval('$this->$key = $reg[$key][0];');
		}
	}

	/**
	 * Devuelve un array asociativo con los datos del registro con el 'id' especificado.
	 * @access public
	 * @param $id (int) es el identificador del registro que se va a cargar. Si no se especifica se toma el valor de la propiedad 'id' de la clase.
	 * @return array asociativo con el siguiente formato: valor['campo1'][0]; valor['campo2'][0]; etc.
	 */ 
	function getById($id = "")
	{	  
		// Verificar parametro
		if (!empty($id))
		{
			$this->id = $id;
		}			   
		
		$sql  = "SELECT * FROM $this->_tablename ";
		$sql .= "WHERE id = $this->id";
		
		// *** Realizar la consutla *** //
		$res = $this->_db->leer($sql);
		
		// *** Convertir a un array asociativo y devolverlo *** //
		return $this->_rs2array($res);
	}

	/**
	 * Inserta un registro en la tabla correspondiente a la clase y carga en la propiedad 'id' el identificador generado en la tabla.
	 * @access public
	 * @return (boolean) true si se pudo insertar correctamente, false en cualquier otro caso.
	 */ 
	function insert()
	{
		// *** Generar el INTO *** //
		$array_into = array();
		foreach($this->_fields as $key => $value)
		{
			   $array_into[] = $key;
		}
		$into = implode(", ", $array_into);
		
		// *** Generar el VALUES *** //
		$array_values = array();
		foreach($this->_fields as $key => $value)
		{                       
			eval('$field = $this->$key;');
			
			if ((!empty($field) || ($field === '0') || ($field === 0) ) && strval($field) != 'NULL' && !($field === '') )
			{
				eval('$array_values[$key] =  $this->$key;');
				
				$array_values[$key] = "'" . addslashes($array_values[$key]) . "'";
			}
			else
			{
			   eval('$array_values[$key] =  \'NULL\';');
			}
	   }
	   $values = implode(", ", $array_values);

		// *** Armar el INSERT *** //
		$sql  = "INSERT INTO $this->_tablename ";
		$sql .= "($into) ";
		$sql .= "VALUES ";
		$sql .= "($values)";
		
		// *** Realizar la consutla *** //
		$ok = $this->_db->ejecutar($sql);

		if (!$ok)
		{
			// No se pudo insertar 
			return false;
		}

		// *** Cargar el id devuelto al insertar *** //
		$this->id = $this->_db->insert_id($this->_tablename, 'id');
		
		// Se insert� correctamente
		return true;
	}
	
	/**
	 * Actualiza el registro con el 'id' especificado, solamente en aquellos campos que hayan sido cargados o contengan valor 'NULL'.
	 * Para los campos no cargados no se modifican los valores actuales de la tabla, esten vac�os o no.
	 * @access public
	 * @param $id (int) es el identificador del registro que se va a actualizar. Si no se especifica se toma el valor de la propiedad 'id' de la clase.
	 * @return (boolean) true si se pudo insertar correctamente, false en cualquier otro caso.
	 */ 
	function update($id = "")
	{
		// Verificar parametro
		if (!empty($id))
		{
			$this->id = $id;
		}			   
		
		// *** Si no esta definido el registro, salir *** /
		if (empty($this->id))
		{
			return ;
		}

		// *** Generar el SET *** //
		$set = "";
		$array_set = array();
		foreach($this->_fields as $key => $value)
		{
			// Tomar el valor del campo
			eval('$field = $this->$key;');

			// Tomar todos los valores no vacios (inclusive el cero)				
			if (!empty($field) || ($field === 0) || !( ($field === '') || (is_null($field)) ))
			{
				if (strval($field) == 'NULL')
				{
					eval('$array_set[$key] = \'NULL\';');
				}
				else
				{
					eval('$array_set[$key] = $this->$key;');
										   
				   	// Agregar las comillas si el campo es de tipo varchar, date o boolean
				   	if ($value == 'varchar' || $value == 'date' || $value == 'text' || $value == 'boolean')
				   	{
						$array_set[$key] = "'" . addslashes($array_set[$key]) . "'";
				   	}
				}
			}
		}

		// Armar el array de esta forma: array['campo'] = 'campo = ' . $value
		foreach($array_set as $field => $value)
		{
			   $array_set[$field] = $field . " = " . $value;
		}
		$set = implode(", ", $array_set);
		
		// *** Generar el WHERE *** //
		$where = "WHERE id = $this->id";
		
		// *** Armar el UPDATE *** //
		$sql  = "UPDATE $this->_tablename SET ";
		$sql .= "$set ";
		$sql .= "$where";

		// *** Realizar la consutla *** //
		$ok = $this->_db->ejecutar($sql);
		
		if (!$ok)
		{
			// Se actualizo correctamente
			return false;
		}
		
		// Se actualiz� correctamente
		return true;
	}
	
	/**
	 * Guarda el objeto en la base de datos. Si hay un id cargado el objeto se actualiza, sino se inserta.
	 * @access public
	 * @return (boolean) true si se pudo grabar correctamente, false en cualquier otro caso.
	 */ 
	function save()
	{
		if ($this->id)
		{
			return $this->update();
		}
		else
		{
			return $this->insert();
		}
	}
	
	/**
	 * Borra registro con el 'id' especificado.
	 * @access public	
	 * @param $id (int) es el identificador del registro que se va a borrar. Si no se especifica se toma el valor de la propiedad 'id' de la clase.
	 * @return (boolean) true si se pudo insertar correctamente, false en cualquier otro caso.
	 */ 
	function delete($id = "")
	{	  
		// Verificar parametro
		if (!empty($id))
		{
			$this->id = $id;
		}			   
		
		// *** Verificar si es un registro valido ***//
		if (empty($this->id))
		{
			return ;
		}
		
		// *** Armar la consulta *** //
		$sql  = "DELETE FROM $this->_tablename ";
		$sql .= "WHERE id = $this->id";
		
		// *** Realizar la consutla *** //
		if (!$this->_db->ejecutar($sql))
		{
			// No se pudo borrar
			return false;
		}
		
		// Se borr� correctamente
		return true;
	}

	/**
	 * Devuelve un string que representa al registro especificado en XML.
	 * El formato es el siguiente: 
	 * 	<Nombre_de_la_Tabla>
	 * 		<campo1>valor_campo1</campo1>
	 * 		<campo2>valor_campo2</campo2>
	 *			...	
	 * 		<campoN>valor_campoN</campoN>		
	 * 	</Nombre_de_la_Tabla>
	 * @access public	
	 * @param $id (int) es el identificador del registro que se va a utilizar. Si no se especifica se toma el valor de la propiedad 'id' de la clase.
	 * @return string representa el registro con el formato XML anterior.
	 */ 
	function toXml($id = "")
	{
		// Verificar parametro
		if (!empty($id))
		{
			$this->load($id);
		}
		
		// Root
		$xml = "<$this->_tablename>";
		
		// Agrego el tag <id>
		$xml .= "<id>";
		$xml .= "$id";
		$xml .= "</id>";
						
		// Agregar los demas items
		foreach ($this->_fields as $key => $value)
		{
			$xml .= "<$key>";
			
			// Agregar valor
			eval('$field = $this->$key;');
			if (!empty($field))
			{
				eval('$xml .= $this->$key;');
			}
			else
			{
				if (is_null($field))
				{
					eval('$xml .= \'NULL\';');
				}
			}
			
			$xml .= "</$key>";
		}
		
		// Cerrar Root
		$xml .= "</$this->_tablename>";
		
		// Devolver el String
		return $xml;
	}

	/**
	 * Carga los campos (atributos de la clase) con los valores contenidos en el array asociativo
	 * pasado como parametro.
	 * @access public
	 * @param $data (array asociativo) es un array que contiene como clave el valor de alg�n campo
	 * y como valor el valor que se le va a asignar al mismo.	
	 * @return void
	 */ 
	function setFields($data)
	{
		// Armar un array con todas las variables de la clase (campos)
		$vars[0] = 'id';
		foreach ($this->_fields as $var => $type)
		{
			$vars[] = $var;
		}

		// Cargar las variables que esten en el array pasado como parametro
		foreach ($data as $field => $value)
		{
			// Si existe el campo, asignarle el valor
			if ($field == 'id' || in_array($field, $vars))
			{
				$this->$field = ($value) ? $value : 'NULL';
			}
		}
	} 
	 
	/**
	 * Devuelve un array con todos los campos de la tabla correspondiente a la clase.
	 * @access public
	 * @return array contiene todos los campos de la tabla correspondiente a la clase.
	 */ 
	function fields()	  
	{				
		$res = array();
		
		// Agregar el campo id, el cual est� en todas las tablas
		$res[] = "id";
		
		// Agregar los demas campos
		foreach($this->_fields as $field => $value)
		{
			$res[] = $field;
		}
		
		return $res;
	}

	/**	
	 * Devuelve un array asociativo, conteniendo para cada campo el valor correspondiente.
	 * @access public	
	 * @return array asociativo con los campos y los valores almacenados.
	 */ 
	function toArray()
	{
		$res = array();
		
		// Agregar el campo id, el cual est� en todas las tablas
		$res['id'] = $this->id;
		
		// Agregar los demas campos
		foreach($this->_fields as $field => $value)
		{
			$res[$field] = $this->{$field};
		}
		
		return $res;		
	}

	/**
	 * Inicia una transacci�n
	 *	@access public
	 * @return void
	 */ 
	function begin()
	{
		$this->_db->begin();
	}

	/**
	 * Hace un rollback de la transacci�n actual
	 *	@access public
	 * @return void
	 */ 
	function rollback()
	{	
		$this->_db->rollback();
	}
	
	/**
	 * Hace un commit de la transacci�n actual
	 * @access public
	 * @return void
	 */ 
	function commit()
	{	
		$this->_db->commit();		
	}

	/**
	 * Devuelve un array asociativo con los registros contenidos en el recordset '$rs'.
	 * @access private	
	 * @param $rs es el resultado de alguna consulta realizada por medio de la clase 'db'.
	 * @return array asociativo con el siguiente formato: valor['campo1'][0]; valor['campo1'][1]; valor['campo2'][0]; etc.	  
	 */ 
	function _rs2array($rs)
	{
		$db = $this->_db;
		$res = array();

		// Pasar los campos a un registro asociativo
		while($r = $db->reg_array($rs))
		{
			// NOTA: 
			// $r es de la forma:
			// 		$r[0] = 55
			// 		$r['id'] = 55
			// 		O sea que esta indexado por un entero y por un string
			
			// Armar un array asociativo obviando los indices numericos					
			foreach ($r as $key => $valor)
			{
					if (strval(intval($key)) == strval($key))
					{
						// Si es numerico paso al siguiente campo
						continue;
					}

					// Agregar valor
					$res[$key][] = $valor;					
			}
		}

		// Devolver el array asociativo
		return $res;
	}	
	
	/// Devuelve un array asociativo con todos los registros de la tabla correspondiente a la clase.
	/// @access public
	/// @param $orderby (string) nombre del campo por el cual se van a ordenar los registros
	/// @return array asociativo con el siguiente formato: valor['campo1'][0]; valor['campo1'][1]; valor['campo2'][0]; etc.
	function getAll($orderby = '')
	{
		// *** Armar la lista de campos en el orden que aparecen en $this->_fields *** //
		$campos = "id";
		foreach($this->_fields as $field => $value)
		{
			$campos .= ", $field";
		}  	  
		
		// *** Armar la consulta *** //
		$sql  = "SELECT ";
		$sql .= $campos;
		$sql .= " FROM $this->_tablename ";
		
		if ($orderby)
			$sql .= "ORDER BY $orderby;";
		
		// *** Realizar la consutla y devolver el resultado *** //
		$res = $this->_db->leer($sql);
		
		// *** Convertir el record set en un array asociativo y devolverlo *** //
		return $this->_rs2array($res);
	}
	
}
?>