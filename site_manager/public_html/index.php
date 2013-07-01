<?php

if($_SERVER['SERVER_NAME'] == 'site_manager')
	define("ENV", "development");	
else 
	define("ENV", "production");	

define('PROJECT_NAME', 'app');


require dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'framework_novo' . DIRECTORY_SEPARATOR . 'start' . DIRECTORY_SEPARATOR . 'includes.php';


$dispatcher = new Dispatcher();
$dispatcher->setDefaultController('index');
$dispatcher->setDefaultAction('index');
$dispatcher->dispatch();

?>