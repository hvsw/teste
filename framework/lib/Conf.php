<?php
Class Conf {
	private static $config = array();
	
	public static function r($name) {
		return isset(self::$config[$name]) ? self::$config[$name] : NULL;
	}
	
	public static function w($name,$value) {
		self::$config[$name] = $value;
	}
	
	public static function getAll() {
		return self::$config;
	}
}


?>