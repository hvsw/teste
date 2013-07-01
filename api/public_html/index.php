<?php

if($_SERVER['SERVER_NAME'] == 'apililohass') {
	error_reporting(E_ALL);	
	define("ENV", "development");	
}
else {
	error_reporting(0);	
	define("ENV", "production");	
}

if(isset($_COOKIE['development'])) {
	error_reporting(E_ALL);
}

define('PROJECT_NAME', 'app');

require dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'start' . DIRECTORY_SEPARATOR . 'includes.php';


$dispatcher = new Dispatcher();
$dispatcher->setDefaultController('index');
$dispatcher->setDefaultAction('index');
$dispatcher->dispatch();

?>