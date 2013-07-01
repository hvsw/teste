<?php
date_default_timezone_set("America/Sao_Paulo");

if(ENV == 'production' && !isset($_COOKIE['development']))
	error_reporting(0);
else {
    ini_set('display_errors', 1);
    error_reporting(E_ALL | E_STRICT);
}

Routes::setRoute(array(
		'/'			=> '/index',
	)
);

Conf::w('database', array(
		'development' => array(
			'server'	=>	'localhost',
			'user' 		=> 	'root',
			'pass'		=>	'root',
			'db'		=>	'site_manager'
		),
		'production' => array(
			'server'	=>	'',
			'user' 		=> 	'',
			'pass'		=>	'',
			'db'		=>	''
		),
	)
);
