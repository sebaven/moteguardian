<?
include_once BASE_DIR . 'clases/listados/clase.Listados.php';
include_once BASE_DIR . 'clases/negocio/clase.Central.php';
include_once BASE_DIR . 'clases/negocio/clase.RecolectorFtp.php';
include_once BASE_DIR . 'clases/negocio/clase.OrigenFtp.php';
include_once BASE_DIR . 'clases/negocio/clase.DatosProceso.php';
include_once BASE_DIR . 'clases/dao/dao.RecolectorFtp.php';
include_once BASE_DIR . 'clases/dao/dao.OrigenFtp.php';
include_once BASE_DIR . 'validadores/MinMaxValueValidator.php';
include_once BASE_DIR . 'validadores/IPValidator.php';
include_once BASE_DIR . 'validadores/NumericInteger.php';


/**
 * @author aamantea
 */
class RecolectorFTPConf extends Action {
	var $tpl = "tpl/central/tpl.RecolectorFTPConf.php";

	function defaults() {
		if(!(($_GET['idCentral']!="") && (!isset($_POST['id_fila_borrar'])))) {
			session_unregister('tabla_origenes');
			session_unregister('filaEditada');
		}
		$this->asignar('modoPasivo','checked');
	}
	
	function inicializar() {
		$this->asignar('options_luegoTransferencia',ComboLuegoTransferencia());
		
		$this->actualizarCabecera();
		
		// Si se ejecut� la acci�n para una modificaci�n, se cargan los datos correspondientes
		if(($_GET['idCentral']!="") && (!isset($_POST['id_fila_borrar']))) {
			$this->cargarDatosExistentesRecolector($_GET['idCentral']);
		}
		$this->asignar('modoPasivo','checked');		
		$this->cargarDatosEscapeadosPorJavascript();
	}

	/**
	 * Esta función es para tomar los catos escapeados por javascript de get, sacarle los caracteres de escapeo y 
	 * guardarlos en el post
	 */
	function cargarDatosEscapeadosPorJavascript(){
		$this->asignar('nombre_central', $_GET['nombreCentral']);
		$this->asignar('codigo_central', $_GET['codigoCentral']);
		$this->asignar('descripcion_central', $_GET['descripcionCentral']);		
	}
	
	/**
	 * Esta funci�n se encarga de actualizar los datos del recolector FTP cada vez
	 * que se recarga la pagina
	 *
	 */
	function actualizarCabecera() {
		//Si los datos ya estaban cargados, se mantienen los valores
		$this->asignar('nombreTecnologia', $_GET['tecnologia']);
		$this->asignar('destino_local', $_POST['destino_local']);
		$this->asignar('id_luegoTransferencia',$_POST['id_luegoTransferencia']);
		if($_POST['tamanio'] == 'on') {
			$this->asignar('chkTamanio','checked'); 
		} else $this->asignar('chkTamanio','');
		if($_POST['firmaSHA'] == 'on') {
			$this->asignar('chkFirmaSHA','checked'); 
		} else $this->asignar('chkFirmaSHA','');
		if($_POST['busqueda_recursiva'] == 'on') {
			$this->asignar('chkBusquedaRecursiva','checked'); 
		} else $this->asignar('chkBusquedaRecursiva','');
	}
	
	
	function validar(&$v) {
		//Validaciones para el agregado de origen
		if(isset($_POST['btnAgregarOrigenFTP'])) {
			$v->add(new Required('direccionIP', 'origenFTP.direccionIP.required'), true);
			$v->add(new Required('ubicacion', 'origenFTP.ubicacion.required'), true);
			$v->add(new Required('puerto', 'origenFTP.puerto.required'), true);
			$v->add(new Required('nroIntentos', 'origenFTP.nroIntentos.required'), true);
			$v->add(new Required('usuarioFTP', 'origenFTP.usuarioFTP.required'), true);
			$v->add(new Required('passwordFTP', 'origenFTP.passwordFTP.required'), true);
			$v->add(new MinMaxValueValidator('puerto', 1, 65535, 'origenFTP.puerto.rango'), true);
			$v->add(new NumericInteger('puerto','origenFTP.puerto.numerico'),true);
			$v->add(new MinMaxValueValidator('nroIntentos', 1, 100, 'origenFTP.nroIntentos.rango'), true);
			$v->add(new NumericInteger('nroIntentos','origenFTP.nroIntentos.numerico'),true);
			$v->add(new IPValidator($_POST['direccionIP'],'origenFTP.direccionIP.formato'), true);
			$v->add(new NumericInteger('timeout','origenFTP.timeout.numerico'),true);			
		}
		
		//Validaciones para el recolector FTP
		if(isset($_POST['btnProcesar'])) {
			$v->add(new Condition($this->validarExistenciaOrigenes(),'recolectorFTP.origenes.required'),true);
		}	
		
	}

	/**
	 * Esta funci�n valida que se haya ingresado al menos un origen valido para el
	 * recolector
	 *
	 * @return true si es valido o false en caso contrario
	 */
	function validarExistenciaOrigenes() {
		if(isset($_SESSION['tabla_origenes'])) {
			$i = 0;
			foreach($_SESSION['tabla_origenes'] as $nroFila => $fila) {
					$i++;
					// Basta con que haya un origen con baja logica en 0 para que valide ok
					if($fila['bajaLogica']==0) return true;
			}
			return false;
		} else return false;
	}
	
	/**
	 * Esta funci�n se encarga de asignarle todos los datos introducidos en los campos
	 * del formulario a un objeto del tipo RecolectorFtp
	 *
	 * @param RecolectorFtp $recolectorFTP
	 * @param string $idCentral
	 */
	function asignarDatosRecolector(&$recolectorFTP, $idCentral) {
		if ($_POST["busqueda_recursiva"]=='on')
				$busquedaRecursiva = TRUE_;
			else
	 			$busquedaRecursiva = FALSE_;

	 	if ($_POST["firmaSHA"]=='on')
				$firmaSHA = TRUE_;
			else
	 			$firmaSHA = FALSE_;	
	 			
	 	if ($_POST["tamanio"]=='on')
				$tamanio = TRUE_;
			else
	 			$tamanio = FALSE_;
	 			
	 	$recolectorFTP->id_central = $idCentral;
	 	$recolectorFTP->destino_local = $_POST['destino_local'];
	 	$recolectorFTP->busqueda_recursiva = $busquedaRecursiva;
	 	$recolectorFTP->borrar_archivos = $_POST["id_luegoTransferencia"];
	 	$recolectorFTP->firma_SHA = $firmaSHA;
	 	$recolectorFTP->tamanio = $tamanio;	 	
	}
	
	
	function procesar(&$nextAction)
	{
		// TODO: Refactorizar esto y ponerlo en un service
		$parametrosAction['pop'] = 1;
		
		$central = new Central();
		$datosCentral = array(nombre => $_POST['nombreCentral'], "codigo" => $_POST['codigoCentral'], "id_tecnologia_recolector" => $_GET['id_tecnologia_recolector'], "id_tecnologia_central" => $_GET['id_tecnologia_central'], "descripcion" => $_POST['descripcionCentral']);
		// Se setean los campos y se hace el save
		$central->setFields($datosCentral);
		$central->baja_logica = FALSE_;
		// Si la central ya existia, se setea el id
		if($_GET['idCentral']!="") {
			$central->id = $_GET['idCentral'];
		}

		if ($central->save()) {
			// Si la central ya existia, se obtiene el recolector existente
			if($_GET['idCentral']!="") {
				$daoRecolectorFTP = new RecolectorFtpDAO();
				$recolectorFTP = $daoRecolectorFTP->getByIdCentral($central->id);
			} else {
				// Sino, se crea un nuevo recolector
				$recolectorFTP = new RecolectorFtp();
				
				// Y se crean los datos_proceso correspondientes
				$datosProceso = new DatosProceso();
				$datosProceso->id_proceso_central = $central->id;
				$datosProceso->id_proceso_actividad_envio = "NULL";
				$datosProceso->id_proceso_nodo_plantilla = "NULL";
				$datosProceso->nombre_estado = "Recolectado por central \"".$central->nombre."\"";
				$datosProceso->save();
			}
			// Se setean los campos y se hace el save
			$this->asignarDatosRecolector($recolectorFTP,$central->id);
			if ($recolectorFTP->save()) {
				foreach ($_SESSION['tabla_origenes'] as $nroFila => $fila) {
					//Se instancia el origen FTP con los datos y se hace el save	
					$origenFTP = new OrigenFtp($recolectorFTP->id, $fila);
					$origenFTP->save();
				}
				// Se eliminan los datos cargados en sesi�n
				$_SESSION['tabla_origenes'] = NULL;
				// Se setea la proxima acci�n
				$nextAction->setNextAction("SalidaPopupRecolectorFTP", "central.configuracion.ok",$parametrosAction);		
			} else {
				$nextAction->setNextAction("SalidaPopupRecolectorFTP", "central.configuracion.error",$parametrosAction);	
			}
		} else {
			$nextAction->setNextAction("SalidaPopupRecolectorFTP", "central.configuracion.error",$parametrosAction);
		}
		
	}
	
	
	/**
	 * Esta funci�n se encarga de agregar un origen FTP a un array, a partir de 
	 * los datos que se han introducido en los campos correspondientes
	 *
	 */
	function agregarOrigenFTP() {
		if(isset($_SESSION['filaEditada'])) {
			// Si se habia seleccionado editar una fila, simplemente se actualizan los datos de dicha fila
			$this->modificarOrigenFTP();
			session_unregister('filaEditada');
		} else { 
			if($_POST['modoPasivo'] == 'on') {
				$modo_pas = "Si";
			} else $modo_pas = "No";
			if (!(is_null($_SESSION['tabla_origenes']))) {
				$matriz = $_SESSION['tabla_origenes'];
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
				$_SESSION['tabla_origenes'] = $matriz; 	
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
					$_SESSION['tabla_origenes'] = $matriz;
				}
			}
		}
		$this->limpiarCamposOrigenFTP();
		$this->asignar('modoPasivo','checked');
	}
	
	function modificarOrigenFTP () {
		$idFilaEditada = $_SESSION['filaEditada']['idFila'];
		if($_POST['modoPasivo'] == 'on') {
			$modo_pas = "Si";
		} else $modo_pas = "No";
		$_SESSION['tabla_origenes'][$idFilaEditada]['direccionIP'] = $_POST['direccionIP'];
		$_SESSION['tabla_origenes'][$idFilaEditada]['ubicacion'] = $_POST['ubicacion'];
		$_SESSION['tabla_origenes'][$idFilaEditada]['puerto'] = $_POST['puerto'];
		$_SESSION['tabla_origenes'][$idFilaEditada]['nroIntentos'] = $_POST['nroIntentos'];
		$_SESSION['tabla_origenes'][$idFilaEditada]['usuarioFTP'] = $_POST['usuarioFTP'];
		$_SESSION['tabla_origenes'][$idFilaEditada]['passwordFTP'] = $_POST['passwordFTP'];
		$_SESSION['tabla_origenes'][$idFilaEditada]['modo_pasivo'] = $modo_pas;
		$_SESSION['tabla_origenes'][$idFilaEditada]['timeout'] = $_POST['timeout'];		
	}
	
	/**
	 * Esta funci�n obtiene el �ltimo n�mero de orden de la lista de origenes
	 *
	 * @return integer
	 */
	function obtenerUltimoNroOrden() {
		$i = 0;
		foreach($_SESSION['tabla_origenes'] as $nroFila => $fila) {
			// Si la fila no esta dada de baja, se incrementa el nro. de orden
			if($fila['bajaLogica'] == 0) $i++;	
		}
		return $i;
	}
	
	/**
	 * Esta funcion se encarga de limpiar todos los campos correspondientes al origen
	 * FTP
	 *
	 */
	function limpiarCamposOrigenFTP() {
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
	 * Esta funcion carga los datos de un recolector FTP ya existente a partir
	 * del id de la central asociada al mismo
	 *
	 * @param string $idCentral
	 */
	function cargarDatosExistentesRecolector($idCentral) {
		// TODO: Refactorizar esto y poner parte en un service
		$daoRecolectorFTP = new RecolectorFtpDAO();
		$recolectorFTP = $daoRecolectorFTP->getByIdCentral($idCentral);
		$this->asignarArray($recolectorFTP->toArray());
		
		if($recolectorFTP->tamanio == TRUE_) {
			$this->asignar('chkTamanio','checked'); 
		} else $this->asignar('chkTamanio','');
		
		if($recolectorFTP->firma_SHA == TRUE_) {
			$this->asignar('chkFirmaSHA','checked'); 
		} else $this->asignar('chkFirmaSHA','');
		
		if($recolectorFTP->busqueda_recursiva == TRUE_) {
			$this->asignar('chkBusquedaRecursiva','checked'); 
		} else $this->asignar('chkBusquedaRecursiva','');
		
		$this->asignar('id_luegoTransferencia',$recolectorFTP->borrar_archivos);
		
		// Se cargan los datos de los origenes correspondientes al recolector
		$this->cargarDatosExistentesOrigenes($recolectorFTP->id);
	}
	
	/**
	 * Esta funcion carga los datos de origenes FTP a partir del id del recolector FTP
	 * asociado a los mismos
	 *
	 * @param string $idCentral
	 */
	function cargarDatosExistentesOrigenes($idRecolector) {
		// TODO: Refactorizar esto y poner lo que se pueda en un service
		$daoOrigenFTP = new OrigenFtpDAO();
		$origenesFTP = $daoOrigenFTP->getByIdRecolectorFtp($idRecolector);
		$i = 0;
		$matriz = array();
		foreach($origenesFTP as $objOrigen => $origen) {
				$i++;
				$matriz[$i] = $this->convertirArrayOrigen($i,$origen->toArray());
		}
		$_SESSION['tabla_origenes'] = $matriz;
	}
	
	
	function convertirArrayOrigen($i, $arrayOrigen) {
		if($arrayOrigen['modo_pasivo'] == TRUE_) {
			$modo_pas = "Si";
		} else $modo_pas = "No";
		$vector = array('idFila' => $i,
						'id' => $arrayOrigen['id'],
						'nroOrden' => $arrayOrigen['nro_orden'],
						'direccionIP' => $arrayOrigen['direccion_ip'], 
			            'ubicacion' => $arrayOrigen['ubicacion_archivos'],
			            'puerto' => $arrayOrigen['puerto'],
			            'nroIntentos' => $arrayOrigen['intentos_conexion'],
			            'usuarioFTP' => $arrayOrigen['usuario'],
						'passwordFTP' => $arrayOrigen['contrasenia'],
						'modo_pasivo' => $modo_pas,
						'timeout' => $arrayOrigen['timeout'],
						'bajaLogica' => $arrayOrigen['baja_logica']);
		return $vector;	
	}
	
	/**
	 * Esta funci�n se encarga de setear la baja logica de un origen ftp en 1 cuando se 
	 * ha seleccionado la opci�n de eliminar ese origen.
	 *
	 */
	function eliminarOrigenFTP() {
		$idFila = $_POST['id_fila_borrar'];
		$i = 0;
		$nroOrden = 1;
		foreach($_SESSION['tabla_origenes'] as $nroFila => $fila) {
				$i++;
				// Si la fila es la seleccionada, se setea la baja logica en 1
				if($fila['idFila']==$idFila) {
					$_SESSION['tabla_origenes'][$i]['bajaLogica'] = 1;
				} else if($_SESSION['tabla_origenes'][$i]['bajaLogica'] == 0) {
					// Si la fila no fue borrada, se le actualiza el nro. de orden correlativo
					$_SESSION['tabla_origenes'][$i]['nroOrden'] = $nroOrden;
					$nroOrden++;
				}
		}
	}
	
	
	function editarOrigenFTP() {
		$idFilaEditada = $_POST['id_fila_borrar'];
		$_SESSION['filaEditada'] = $_SESSION['tabla_origenes'][$idFilaEditada];
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