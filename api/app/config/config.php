<?php
date_default_timezone_set("America/Sao_Paulo");

Routes::setRoute(array(
		'/'			=> '/index',
		'/index'	=> '/index/index'
	)
);

Conf::w('database', array(
		'development' => array(
			'server'	=>	'localhost',
			'user' 		=> 	'root',
			'pass'		=>	'root',
			'db'		=>	'msg'
		)
	)
);
 
Conf::w('database_app',array(
	'production' => array(
		'user'		=>	'lilohass',
		'pass'		=>	'lilo911014',
		'db'		=>	'lilohass_app',
		'server'	=>	'localhost',			
	),
	'development' => array(
		'user'		=>	'root',
		'pass' 		=> 	'root',
		'server'	=>	'localhost',
		'db'		=>	'lilohass_app'
	)
));

Conf::w('urbanairship',array(
	'appkey'	=>	'od3ipJ1-T8aOC6B9xs5JDg',
	'secret'	=>	'UA0VrDFXTJSqtEIg7Hgmnw',
	'url'		=>	'https://go.urbanairship.com/api/push/broadcast/',
	'pass'		=>	'LIlo.hass*911014*',
));

Conf::w('correios',array(
	'url'	=>	'http://websro.correios.com.br/sro_bin/sroii_xml.eventos',
	'user'	=>	'ECT',
	'pass'	=>	'SRO',
));

