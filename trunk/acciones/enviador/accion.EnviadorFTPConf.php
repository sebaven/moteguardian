<?
include_once BASE_DIR . 'clases/listados/clase.Listados.php';
include_once BASE_DIR . 'clases/negocio/clase.Central.php';
include_once BASE_DIR . 'clases/negocio/clase.EnviadorFtp.php';
include_once BASE_DIR . 'clases/negocio/clase.DestinoFtp.php';
include_once BASE_DIR . 'clases/dao/dao.EnviadorFtp.php';
include_once BASE_DIR . 'clases/negocio/clase.ActividadEnviador.php';
include_once BASE_DIR . 'clases/dao/dao.DestinoFtp.php';
include_once BASE_DIR . 'validadores/MinMaxValueValidator.php';
include_once BASE_DIR . 'validadores/IPValidator.php';
include_once BASE_DIR . 'validadores/NumericInteger.php';


/**
 * @author aamantea
 */
class EnviadorFTPConf extends Action {
	var $tpl = "tpl/enviador/tpl.EnviadorFTPConf.php";

	function defaults() {
		session_unregister('filaEditada');
		$this->asignar('modoPasivo','checked');
	}
	
	function inicializar() {
		
		// Cada vez que se abre la pagina, se limpia la tabla de destinos de la sesion
		if (!(isset($_POST['limpiarSession']))) {
			$_SESSION['tabla_destinos'] = NULL;
		}
		
		$this->actualizarCabecera();
		
		// Si se ejecut� la acci�n para una modificaci�n, se cargan los datos correspondientes
		if(($_GET['idActividadEnvio']!="") && (!isset($_POST['id_fila_borrar']))) {			
			$this->cargarDatosExistentesEnviador($_GET['idActividadEnvio']);
		}
	}
	
	
	/**
	 * Esta funci�n se encarga de actualizar los datos del enviador FTP cada vez
	 * que se recarga la pagina
	 */
	function actualizarCabecera() {
		//Si los datos ya estaban cargados, se mantienen los valores
		$this->asignar('nombreTecnologia', $_GET['tecnologia']);
		$this->asignar('formato_renombrado', $_POST['formato_renombrado']);
	}
	
	
	function validar(&$v) {
		//Validaciones para el agregado de destino
		if(isset($_POST['btnAgregarDestinoFTP'])) {
			$v->add(new Required('direccionIP', 'destinoFTP.direccionIP.required'), true);
			$v->add(new Required('ubicacion', 'destinoFTP.ubicacion.required'), true);
			$v->add(new Required('puerto', 'destinoFTP.puerto.required'), true);
			$v->add(new Required('nroIntentos', 'destinoFTP.nroIntentos.required'), true);
			$v->add(new Required('usuarioFTP', 'destinoFTP.usuarioFTP.required'), true);
			$v->add(new Required('passwordFTP', 'destinoFTP.passwordFTP.required'), true);
			$v->add(new MinMaxValueValidator('puerto', 1, 65535, 'destinoFTP.puerto.rango'), true);
			$v->add(new NumericInteger('puerto','destinoFTP.puerto.numerico'),true);
			$v->add(new MinMaxValueValidator('nroIntentos', 1, 100, 'destinoFTP.nroIntentos.rango'), true);
			$v->add(new NumericInteger('nroIntentos','destinoFTP.nroIntentos.numerico'),true);
			$v->add(new IPValidator($_POST['direccionIP'],'destinoFTP.direccionIP.formato'), true);
			$v->add(new NumericInteger('timeout','destinoFTP.timeout.numerico'),true);
		}
		
		//Validaciones para el enviador FTP
		if(isset($_POST['btnProcesar'])) {
			$v->add(new Condition($this->validarExistenciaDestinos(),'enviadorFTP.destinos.required'),true);
		}	
		
	}

	/**
	 * Esta funci�n valida que se haya ingresado al menos un destino valido para el
	 * enviador
	 *
	 * @return true si es valido o false en caso contrario
	 */
	function validarExistenciaDestinos() {
		if(isset($_SESSION['tabla_destinos'])) {
			$i = 0;
			foreach($_SESSION['tabla_destinos'] as $nroFila => $fila) {
					$i++;
					// Basta con que haya un destino con baja logica en 0 para que valide ok
					if($fila['bajaLogica']==0) return true;
			}
			return false;
		} else return false;
	}
	
	/**
	 * Esta funci�n se encarga de asignarle todos los datos introducidos en los campos
	 * del formulario a un objeto del tipo EnviadorFtp
	 *
	 * @param EnviadorFtp $enviadorFTP
	 * @param string $idActividadEnvio
	 */
	function asignarDatosEnviador(&$enviadorFTP, $idActividadEnvio) {	
	 	$enviadorFTP->id_actividad_envio = $idActividadEnvio;
	 	$enviadorFTP->formato_renombrado = $_POST['formato_renombrado']; 	
	}
	
	
	function procesar(&$nextAction) {
		// TODO: Refactorizar esto y poner lo que se pueda en un service
		$parametrosAction['pop'] = 1;
						
		$idActividadEnvio = $_GET['idActividadEnvio'];

		$actividadEnvio = new ActividadEnviador($idActividadEnvio);
		$actividadEnvio->temporal = FALSE_;
		$actividadEnvio->save();		
		
		// Se obtiene el enviador existente
		$daoEnviadorFTP = new EnviadorFtpDAO();
		$enviadorFTP = $daoEnviadorFTP->getByIdActividadEnvio($idActividadEnvio);

		// Si no exist�a ningun enviador asociado a esa actividad, se crea uno nuevo
		// TODO: Revisar que devuelve el DAO cuando no existe!!!
		if(!isset($enviadorFTP)) {
			$enviadorFTP = new EnviadorFtp();
		}
		// Se setean los campos y se hace el save
		$this->asignarDatosEnviador($enviadorFTP,$idActividadEnvio);
		if ($enviadorFTP->save()) {
			foreach ($_SESSION['tabla_destinos'] as $nroFila => $fila) {
				//Se instancia el destino FTP con los datos y se hace el save	
				$destinoFTP = new DestinoFtp($enviadorFTP->id, $fila);
				$destinoFTP->save();
			}
			// Se eliminan los datos cargados en sesi�n
			$_SESSION['tabla_destinos'] = NULL;
			// Se setea la proxima acci�n
			$nextAction->setNextAction("SalidaPopupEnviadorFTP", "enviadorFTP.configuracion.ok",$parametrosAction);		
		} else {
			$nextAction->setNextAction("SalidaPopupEnviadorFTP", "enviadorFTP.configuracion.error",$parametrosAction);	
		}
	}
	
	
	/**
	 * Esta funci�n se encarga de agregar un destino FTP a un array, a partir de 
	 * los datos que se han introducido en los campos correspondientes
	 *
	 */
	function agregarDestinoFTP() {
		if(isset($_SESSION['filaEditada'])) {
			// Si se habia seleccionado editar una fila, simplemente se actualizan los datos de dicha fila
			$this->modificarDestinoFTP();
			session_unregister('filaEditada');
		} else {
			if($_POST['modoPasivo'] == 'on') {
				$modo_pas = "Si";
			} else $modo_pas = "No";
			if (!(is_null($_SESSION['tabla_destinos']))){
				$matriz = $_SESSION['tabla_destinos'];
				$cant_filas = count($matriz);
				$vector = array('idFila' => $cant_filas + 1,
								'nroOrden' => $this->obtenerUltimoNroOrden() + 1,
								'direccionIP' => $_POST['direccionIP'], 
					            'ubicacion' => $_POST['ubicacion'],
					            'puerto' => $_POST['puerto'],
					            'nroIntentos' => $_POST['nroIntentos'],
					            'usuarioFTP' => $_POST['usuarioFTP'],
								'passwordFTP' => $_POST['passwordFTP'],
								'modo_pasivo' => $modo_pas,
								'timeout' => $_POST['timeout'],
								'bajaLogica' => 0);
				$matriz[$cant_filas + 1] =	$vector;
				$_SESSION['tabla_destinos'] = $matriz; 	
			} else {
				if (isset($_POST['direccionIP'])){
					$vector = array('idFila'=> 1,
									'nroOrden'=> 1,
									'direccionIP'=> $_POST['direccionIP'], 
					                'ubicacion' => $_POST['ubicacion'],
					                'puerto' => $_POST['puerto'],
					                'nroIntentos' => $_POST['nroIntentos'],
					                'usuarioFTP' => $_POST['usuarioFTP'],
									'passwordFTP' => $_POST['passwordFTP'],
									'modo_pasivo' => $modo_pas,
									'timeout' => $_POST['timeout'],
									'bajaLogica' => 0);
					$matriz = array(1 => $vector);
					$_SESSION['tabla_destinos'] = $matriz;
				}
			}	
			
		}
		$this->limpiarCamposDestinoFTP();
		$this->asignar('modoPasivo','checked');
	}
	
	
	function modificarDestinoFTP () {
		$idFilaEditada = $_SESSION['filaEditada']['idFila'];
		if($_POST['modoPasivo'] == 'on') {
			$modo_pas = "Si";
		} else $modo_pas = "No";
		$_SESSION['tabla_destinos'][$idFilaEditada]['direccionIP'] = $_POST['direccionIP'];
		$_SESSION['tabla_destinos'][$idFilaEditada]['ubicacion'] = $_POST['ubicacion'];
		$_SESSION['tabla_destinos'][$idFilaEditada]['puerto'] = $_POST['puerto'];
		$_SESSION['tabla_destinos'][$idFilaEditada]['nroIntentos'] = $_POST['nroIntentos'];
		$_SESSION['tabla_destinos'][$idFilaEditada]['usuarioFTP'] = $_POST['usuarioFTP'];
		$_SESSION['tabla_destinos'][$idFilaEditada]['passwordFTP'] = $_POST['passwordFTP'];
		$_SESSION['tabla_destinos'][$idFilaEditada]['modo_pasivo'] = $modo_pas;
		$_SESSION['tabla_destinos'][$idFilaEditada]['timeout'] = $_POST['timeout'];		
	}
	
	
	/**
	 * Esta funci�n obtiene el �ltimo n�mero de orden de la lista de destinos
	 *
	 * @return integer
	 */
	function obtenerUltimoNroOrden() {
		$i = 0;
		foreach($_SESSION['tabla_destinos'] as $nroFila => $fila) {
			// Si la fila no esta dada de baja, se incrementa el nro. de orden
			if($fila['bajaLogica'] == 0) $i++;	
		}
		return $i;
	}
	
	
	/**
	 * Esta funcion se encarga de limpiar todos los campos correspondientes al destino
	 * FTP
	 *
	 */
	function limpiarCamposDestinoFTP() {
		$this->asignar('direccionIP',NULL);
		$this->asignar('ubicacion',NULL);
		$this->asignar('puerto',NULL);
		$this->asignar('nroIntentos',NULL);
		$this->asignar('usuarioFTP',NULL);
		$this->asignar('passwordFTP',NULL);
		$this->asignar('modoPasivo',NULL);
		$this->asignar('timeout',NULL);
	}

	
	/**
	 * Esta funcion carga los datos de un enviador FTP ya existente a partir
	 * del id de la actividad_envio asociada al mismo
	 *
	 * @param string $idActividadEnvio
	 */
	function cargarDatosExistentesEnviador($idActividadEnvio) {
		// TODO: Refactorizar esto y poner parte en un service
		$daoEnviadorFTP = new EnviadorFtpDAO();
		$enviadorFTP = $daoEnviadorFTP->getByIdActividadEnvio($idActividadEnvio);		
		if(isset($enviadorFTP)) {
			$this->asignarArray($enviadorFTP->toArray());
			// Se cargan los datos de los destinos correspondientes al enviador
			$this->cargarDatosExistentesDestinos($enviadorFTP->id);		
		}
	}
	
	/**
	 * Esta funcion carga los datos de los destinos FTP a partir del id del enviador FTP
	 * asociado a los mismos
	 *
	 * @param string $idEnviador
	 */
	function cargarDatosExistentesDestinos($idEnviador) {
		// TODO: Refactorizar esto y poner lo que se pueda en un service
		$daoDestinoFTP = new DestinoFtpDAO();
		$destinosFTP = $daoDestinoFTP->getByIdEnviadorFtp($idEnviador);
		$i = 0;
		$matriz = array();
		foreach($destinosFTP as $objDestino => $destino) {
				$i++;
				$matriz[$i] = $this->convertirArrayDestino($i,$destino->toArray());
		}
		$_SESSION['tabla_destinos'] = $matriz;
	}
	
	
	function convertirArrayDestino($i, $arrayDestino) {
		if($arrayDestino['modo_pasivo'] == TRUE_) {
			$modo_pas = "Si";
		} else $modo_pas = "No";
		$vector = array('idFila' => $i,
						'id' => $arrayDestino['id'],
						'nroOrden' => $arrayDestino['nro_orden'],
						'direccionIP' => $arrayDestino['direccion_ip'], 
			            'ubicacion' => $arrayDestino['ubicacion_archivos'],
			            'puerto' => $arrayDestino['puerto'],
			            'nroIntentos' => $arrayDestino['intentos_conexion'],
			            'usuarioFTP' => $arrayDestino['usuario'],
						'passwordFTP' => $arrayDestino['contrasenia'],
						'modo_pasivo' => $modo_pas,
						'timeout' => $arrayDestino['timeout'],
						'bajaLogica' => $arrayDestino['baja_logica']);
		return $vector;	
	}
	
	/**
	 * Esta funci�n se encarga de setear la baja logica de un destino ftp en 1 cuando se 
	 * ha seleccionado la opci�n de eliminar ese destino.
	 *
	 */
	function eliminarDestinoFTP() {
		$idFila = $_POST['id_fila_borrar'];
		$i = 0;
		$nroOrden = 1;
		foreach($_SESSION['tabla_destinos'] as $nroFila => $fila) {
			$i++;
			// Si la fila es la seleccionada, se setea la baja logica en 1
			if($fila['idFila']==$idFila) {
				$_SESSION['tabla_destinos'][$i]['bajaLogica'] = 1;
			} else if($_SESSION['tabla_destinos'][$i]['bajaLogica'] == 0) {
				// Si la fila no fue borrada, se le actualiza el nro. de orden correlativo
				$_SESSION['tabla_destinos'][$i]['nroOrden'] = $nroOrden;
				$nroOrden++;
			}
		}
	}
	
	function editarDestinoFTP() {
		$idFilaEditada = $_POST['id_fila_borrar'];
		$_SESSION['filaEditada'] = $_SESSION['tabla_destinos'][$idFilaEditada];
		$this->asignar('direccionIP',$_SESSION['filaEditada']['direccionIP']);
		$this->asignar('ubicacion',$_SESSION['filaEditada']['ubicacion']);
		$this->asignar('puerto',$_SESSION['filaEditada']['puerto']);
		$this->asignar('nroIntentos',$_SESSION['filaEditada']['nroIntentos']);
		$this->asignar('usuarioFTP',$_SESSION['filaEditada']['usuarioFTP']);
		$this->asignar('passwordFTP',$_SESSION['filaEditada']['passwordFTP']);
		if($_SESSION['filaEditada']['modo_pasivo'] == "Si") $this->asignar('modoPasivo','checked');	
		$this->asignar('timeout',$_SESSION['filaEditada']['timeout']);
	}
	
}
?>