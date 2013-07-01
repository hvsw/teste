<?php
/**
 * Framework configs
 */
define('DS',DIRECTORY_SEPARATOR);
define('FRAMEWORK', dirname(dirname(__FILE__))); 
define('LIB', FRAMEWORK . DS . 'lib');
define('LOGS', FRAMEWORK . DS . 'logs');

/**
 * Application Configs
 */
if (!defined('BASE_PATH'))
	define('BASE_PATH', dirname(str_replace('/', DS, $_SERVER['SCRIPT_FILENAME'])));

if (!defined('WEB_PUBLIC')) 
	define('WEB_PUBLIC', BASE_PATH);

if (!defined('APP')) 
	define('APP', dirname(BASE_PATH).DS.PROJECT_NAME);