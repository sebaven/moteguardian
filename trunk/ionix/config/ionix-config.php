<?
// ---------------------------------------- Configuraci�n Actions
// Prefijo utilizado para los nombres de los botones
define('PREFIX_BUTTON', 'btn');

// Prefijo utilizado para los nombres de los inputs ocultos
define('PREFIX_HIDDEN', '_');

// Prefijo utilizado para los nombres de los inputs ocultos
define('PREFIX_CHECKBOX', 'chk');

// -------------------------------------------------- Directorios
if(!defined('BASE_DIR')) {
	define('BASE_DIR',  realpath('.') . '/');
}
define('FWK_DIR', BASE_DIR.'ionix/');
define('VALIDATORS', FWK_DIR . 'validators/');
define('ACTIONS', FWK_DIR . 'actions/');
define('APPLICATION', FWK_DIR . 'app/');
define('PRESENTATION', FWK_DIR . 'presentation/');
define('HELPERS', FWK_DIR . 'helpers/');
define('DATA', FWK_DIR . 'data/');
define('TEST', FWK_DIR . 'test/');
define('SIMPLE_TEST', FWK_DIR . 'test/simpletest/');
define('COMMON', FWK_DIR . 'common/');
define('CONFIG', BASE_DIR . 'config/');

// ------------------------------------------------ Configuraci�n
define('DATASOURCES_FILE', 'datasources.xml');
define('ACTIONS_FILE', 'config/actions.xml');
define('APP_CONFIG_FILE', 'config/application.xml');
define('LOG_FILE', 'log/errors.log');
define('PERMISSION_FILE', 'config/permisos.xml');
define('LDAP_FILE','ldap.xml');

// ----------------------------------------------- AbstractEntity
define('TYPE_INT', 'int');
define('TYPE_VARCHAR', 'varchar');	
define('TYPE_TEXT', 'text');
define('TYPE_BOOLEAN', 'boolean');
define('TYPE_DATE', 'date');

?>