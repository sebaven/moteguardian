<?
require_once('ionix-config.php');

// ----------------------------------------------- Acceso a datos
require_once(DATA . 'AbstractEntity.php');
require_once(DATA . 'AbstractDAO.php');
require_once(DATA . 'Db.php');
require_once(DATA . 'DbPg.php');
require_once(DATA . 'DbMySql.php');
require_once(DATA . 'ConnectionManager.php');

// -------------------------------------------------- Simple Test
require_once(SIMPLE_TEST . 'unit_tester.php');
require_once(SIMPLE_TEST . 'reporter.php');

// -------------------------------------------------------- Test
require_once(TEST . 'BaseTestCase.php');
require_once(TEST . 'ActionTestCase.php');

require_once(VALIDATORS . 'Validator.php');

?>