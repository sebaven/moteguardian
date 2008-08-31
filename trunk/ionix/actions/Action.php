<?
/**
 * Est� clase es extendida por cada una de las nuevas acciones que se generan. 
 * Representa el ciclo de vida normal de un formulario Web.
 * 
 * ----------------------------------------
 *      iOnix - PHP Framework - 2006
 * ----------------------------------------
 * Jorge Barbosa      <jmbarbosa@gmail.com>
 * Alejandro Molinari <amolinari@gmail.com>
 * ----------------------------------------
 *
 * @version 1.6
 * @package actions
 * @author jbarbosa
 * @author amolinari
 */
class Action
{
	/**
	 * Array donde se van cargando los datos de cada uno de los campos del formulario
	 * @access protected
	 * @var array
	 */
	var $values;

	/**
	 * Path relativo al archivo .tpl que se va mostrar
	 * @access protected
	 * @var string
	 */
	var $tpl;

	/**
	 * Objeto ErrorCollection donde se van a guardar acumulando los errores de validaci�n
	 * @access protected
	 * @var object ErrorCollection
	 */
	var $errors;

	/**
	 * Contiene la referencia a un objeto de la clase Template
	 * @access private
	 * @var object
	 */
	var $_template;

	/**
	 * Contiene la referencia a un objeto de la clase ValidatorBuilder, que se utiliza para agregar los validares
	 * @access private
	 * @var object
	 */
	var $_validatorBuilder;

	/**
	 * Este flag se utiliza para indicar si la acci�n se est� ejecutando con una pantalla asociada. Si est� en false
	 * no se ejecutan las redirecciones a otras acciones.
	 * @access private
	 * @var bool
	 */
	var $_redirect = false;
	
	/**
	 * Referencia a la pr�xima acci�n a ejecutarse cuando termine la actual
	 * @access private
	 * @var object instancia de la clase ActionConnector
	 */
	var $_nextAction;

	/**
	 * Constructor de la clase. Realiza la incializaci�n de todas las propiedades
	 * 
	 * @access public
	 * @return void
	 */
	function Action()
	{
		$this->_validatorBuilder = new ValidatorBuilder();
		$this->_template = new Template();
		$this->values = array();
		$this->errors = new ErrorCollection();
	}
	
	/**
	 * Este m�todo es llamado luego de hacer el submit del formulario para verficiar si los datos son ingresados
	 * son correctos, el mismo carga los errores de validaci�n en $this->errors. Debe ser sobreescrito.
	 * 
	 * @access protected
	 * @return void
	 */
	function validar(&$validatorBuilder)
	{}
	
	/**
	 * Este m�todo es llamado luego que el formulario ha sido validado sin ning�n error. Deber�a ser
	 * sobreescrito para implementar la funcionalidad especifica de procesamiento.
	 * 
	 * @access protected
	 * @return void
	 */
	function procesar(&$nextAction)
	{}

	/**
	 * Este m�todo es llamdado para mostrar el formulario, imprimiendo
	 * errores de valiaci�n si el formulario si corresponde.
	 * Antes de mostrar el formulario se asignan las variables autom�ticamente
	 * 
	 * @access protected
	 * @return void
	 */
	function mostrar()
	{
		if (! $this->errors->isEmpty())
		{
			$this->asignar('errores', $this->errors->getHTMLErrors());
		}

		// Asignar las variables por defecto o las enviadas por el POST ($values)
		if ($this->values && $this->tpl)
		{
			foreach ($this->values as $key => $value)
			{
				$this->_template->asignar($key, $value);
			}
		}

		// Mostrar el formulario
		if ($this->tpl)
		{
			$this->_template->mostrar($this->tpl);
		}
	}
			
	/**
	 * Este m�todo es llamado antes de la primera carga del formulario. Debe ser sobreescrito de acuerdo a las necesidades.
	 * Por defecto esta implementaci�n carga los mensajes que estt�n dirigidos a la acci�n.
	 * 
	 * @access protected
	 * @return void
	 */
	 function defaults() {
        // Asignar el mensaje
        if (!empty($_GET['m'])) {
            $error = $_GET['error'] == '1';
            $this->asignar('mensaje', MessageFactory::getMensaje($_GET['m'], $error));
        }
     }
//	function defaults()
//	{
//		// Asignar el mensaje
//		if (!empty($_GET['m']))
//		{
//			$error = ($_GET['m'] == 'error') ? 1 : 0;
//			$this->asignar('mensaje', MessageFactory::getMensaje($_GET['m'], $error));
//		}
//	}
		
	/**
	 * Asigna un valor a una variable
	 * 
	 * @access public
	 * @param $value (string) nombre de la variable
	 * @param $data (string) valor de la variable
	 * @return void
	 */
	function asignar($value, $data)
	{
		$this->values[$value] = $data;
	}
		
	/**
	 * Asigna los valores contenidos en el array a las variables
	 * 
	 * @param $data (array) contiene como clave el nombre de la variable y como valor el valor a asignarle
	 * @access public
	 * @return void
	 */
	function asignarArray($data)
	{
		if ($data)
		{
			foreach ($data as $key => $value)
			{
				$this->values[$key] = $value;
			}
		}
	}
	
	/**
	 * Este m�todo es llamado antes de mostrar el formulario, sin importar si es la primera vez o si es 
	 * luego de un error de validaci�n.
	 * Debe ser sobreescrito de acuerdo a las necesidades.
	 * 
	 * @access protected
	 * @return void
	 */
	function inicializar()
	{}
				
	/**
	 * Asgina los valores de internacionalizaci�n y asigna el mensaje indicado para la acci�n.
	 * Para setear estos valores hay que usar el array $keys dentro del template.
	 * 
	 * @access protected
	 * @return void
	 */
	function i18n()
	{
		$keys = PropertiesHelper::GetKeys();
		// Asignar las claves
		$this->asignar("keys", $keys);
	}
	
	/**
	 * Este m�todo produce el llamado de cada uno de los m�todos, de acuerdo a la instancia en el workflow
	 * 
	 * @access public
	 * @return void
	 */
	function ejecutarCiclo()
	{
		// Asignar los valores de internacionalizaci�n
		$this->i18n();
		$this->_redirect = true;

		
		// Si la acci�n no tiene template se ejecuta el ciclo como si ya fuera la segunda entrada, o sea, 
		// se ejecuta el procesar como si ya fuera valido
		if (! $this->tpl)
		{
			$this->ejecutar(false, true);
			return;
		}
		
		// Se hizo un post del form?
		// A los botones hechos con imagenes se le agregan el postfijo _x
		if ($_REQUEST[PREFIX_BUTTON . 'Procesar'] || key_exists(PREFIX_BUTTON . 'Procesar_x', $_REQUEST))
		{
			$this->ejecutar(true, true);
		}
		else
		{
		
			if ($metodo = $this->_getMetodo())
			{				
				$this->validar($this->_validatorBuilder);
				$this->_validar(false);
				
				$this->_reinicializar(false);

				if ($this->errors->isEmpty())
				{			
					// Ejecutar el metodo
					eval('$this->' . $metodo . '();');
				}
				
				$this->mostrar();
            }
			else
			{
				$this->iniciar(true);
			}
		}
	}
	
	/**
	 * Este m�todo se ejecuta la primera vez que se carga la acci�n
	 * 
	 * @access public
	 * @param $mostrar (bool) true si se muestra el formulario, false si no se muestra
	 * @return void
	 */
	function iniciar($mostrar = false)
	{
		// Cargar valores previos de la carga del formulario
		$this->inicializar();

		// Si no es un submit, cargar los valores por defecto del formulario y mostrarlo
		$this->defaults();

		if ($mostrar)
		{
			$this->mostrar();
		}
	}
							
	/**
	 * Ejecuta el procesamiento de la acci�n, primero verificando si la misma valida y 
	 * luego llamando al m�todo de procesamiento.
	 * 
	 * @access public
	 * @param $mostrar (bool) true si se muestra el formulario, false si no se muestra
	 * @param $executeNextAction (bool) true si se ejecuta la pr�xima accion al terminar la ejecuci�n
	 * false en caso contrario
	 * @return void
	 */
	function ejecutar($mostrar = false, $executeNextAction = false)
	{
		$this->validar($this->_validatorBuilder);
		$this->_validar(true);
		
		if (! $this->errors->isEmpty())
		{
			$this->_reinicializar($mostrar);
		}
		else
		{
			// Realizar el procesamiento del formulario
			$this->_nextAction = new ActionConnector();
			$this->procesar($this->_nextAction);

			if ($executeNextAction)
			{
				$this->_nextAction->execute();
			}
		}
	}
	
	/**
	 * Indica si la acci�n es valida o no
	 * 
	 * @access public
	 * @return (bool) true si se valid� correctamente, false en caso contrario
	 */
	function isValid()
	{
		return $this->errors->isEmpty();
	}
	
	/**
	 * Devuelve el nombre del metodo ejecutado en la pantalla. El mismo se arma como PREFIX_BUTTON + nombre_metodo. Si no se encuentra
	 * ninguna variable con este nombre se devuelve un string vac�o
	 * 
	 * @access private
	 * @return (string) nombre del m�todo ejecutado o un string vac�o
	 */
	function _getMetodo()
	{
		foreach ($_REQUEST as $name => $value)
		{
			if (substr($name, 0, strlen(PREFIX_BUTTON)) == PREFIX_BUTTON)
			{
				if (strstr($name, "_x"))
				{
					// Es un bot�n type="image"
					return substr($name, strlen(PREFIX_BUTTON), strlen($name) - 5);
				}
				else
				{
					return substr($name, strlen(PREFIX_BUTTON)); ;
				}
			}
		}
			
		return '';
	}
	
	/**
	 * Ejecuta el m�todo inicializar y carga los valores en el formulario (seg�n lo especificado por par�metro)
	 * 
	 * @access private
	 * @param $mostrar (bool) true si se muestra el formulario, false si no se muestra
	 * @return void
	 */
	function _reinicializar($mostrar = false)
	{
		// Cargar valores previos de la carga del formulario
		$this->inicializar();

		// Cargar los valores que van a ir en el formulario
		$this->_cargarValues();

		// Mostrar el formulario y los errores de validacion
		if ($mostrar)
		{
			$this->mostrar();
		}
	}
	
	/**
	 * Carga los valores de las variables de acuerdo al estado del workflow y al tipo de variable del formulario
	 * 
	 * @access private
	 * @return void
	 */
	function _cargarValues()
	{
		foreach ($_REQUEST as $key => $value)
		{
			// Si no es un button o un hidden, incluir la clave y el valor en el array
			if (! (strstr($key, PREFIX_BUTTON) || substr($key, 0, 1) == PREFIX_HIDDEN) )
			{
				// Si es un checkbox, poner como valor 'checked'
				if (strstr($key, PREFIX_CHECKBOX))
				{
					$this->values[$key] = 'checked';
				}
				// Text, select
				else
				{
					// Clean data
					//$value = htmlentities($value);
								
					
					// Si esta activado "magic quotes" quitar los slashes
					if (get_magic_quotes_gpc())
					{
						$this->values[$key] = stripslashes($value);
					}
					else
					{
						$this->values[$key] = $value;
					}
				}
			}
		}
	}
	
	/**
	 * Este m�todo se llama luego de hacer un Submit del formulario. Carga el array $this->errores con cada uno de los
	 * campos marcados como obligatorios, con el mensaje especificado o con un mensaje por defecto.
	 * 
	 * @access private
	 * @param $validate_all (bool) true si se ejecutan todos los validadores, false si se toman aquellos que cumplan
	 * la condici�n.
	 * @return void
	 */
    function _validar($validate_all)
    {
        if ($validate_all)
        {
            $this->errors = $this->_validatorBuilder->validateAll();
        }
        else
        {
            $this->errors = $this->_validatorBuilder->validateOnCondition();
        }
    }
}
?>