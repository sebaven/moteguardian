<?
error_reporting(E_ALL & ~E_NOTICE);

require_once('ionix-config.php');

// --------------------------------------------------- Validators
require_once(VALIDATORS . 'Validator.php');
require_once(VALIDATORS . 'ValidatorBuilder.php');
require_once(VALIDATORS . 'Required.php');
require_once(VALIDATORS . 'Numeric.php');
require_once(VALIDATORS . 'DateField.php');
require_once(VALIDATORS . 'Email.php');
require_once(VALIDATORS . 'Condition.php');

// ---------------------------------------------------- Accciones
require_once(ACTIONS . 'Action.php');
require_once(ACTIONS . 'ActionFactory.php');
require_once(ACTIONS . 'ActionConnector.php');
require_once(ACTIONS . 'FrontController.php');

// --------------------------------------------------- Aplicaci�n
require_once(APPLICATION . 'Application.php');
require_once(APPLICATION . 'ActionConfiguration.php');
require_once(APPLICATION . 'AppConfiguration.php');

// ------------------------------------------------- Presentaci�n
require_once(PRESENTATION . 'Template.php');
require_once(PRESENTATION . 'MessageFactory.php');
require_once(PRESENTATION . 'PropertiesHelper.php');
require_once(PRESENTATION . 'ComboControl.php');
require_once(PRESENTATION . 'DateControl.php');
require_once(PRESENTATION . 'PresentationUtil.php');

// ----------------------------------------------------- Helpers
require_once(HELPERS . 'Registry.php');
require_once(HELPERS . 'RegistryHelper.php');
require_once(HELPERS . 'PermissionHelper.php');

// ----------------------------------------------- Acceso a datos
require_once(DATA . 'AbstractEntity.php');
require_once(DATA . 'AbstractDAO.php');
require_once(DATA . 'Db.php');
require_once(DATA . 'DbPg.php');
require_once(DATA . 'DbMySql.php');
require_once(DATA . 'DbODBC.php');
require_once(DATA . 'ConnectionManager.php');

// ------------------------------------------------------- Com�n
require_once(COMMON . 'ErrorHandler.php');
require_once(COMMON . 'ErrorCollection.php');

// -------------------------------------------------- Simple Test
require_once(SIMPLE_TEST . 'unit_tester.php');
require_once(SIMPLE_TEST . 'reporter.php');

// -------------------------------------------------------- Test
require_once(TEST . 'BaseTestCase.php');
require_once(TEST . 'ActionTestCase.php');
?>