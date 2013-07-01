<?php
class Support {

	public static function import($type = FALSE, $name = FALSE) {
		if($type && $name) {
			$action = 'import'.$type;
			self::$action($name);
		}
		else if (!$type && $name) {
			self::includeFile($name);
		}
	}
	
	public static function importAppController($name = FALSE) {
		if($name) {
			$path = APP.DS.'controller'.DS.$name.DS.'controller.php';
			self::includeFile($path);
		}
	}
	
	public static function importAppLib($name = FALSE) {
		if($name) {
			$path = APP.DS.'lib'.DS.$name.'.php';
			self::includeFile($path);
		}
	}
	
	public static function importFrameworkError($name=FALSE) {
		if($name) {
			$path = LIB.DS.'Error'.DS.$name.'.php';
			self::includeFile($path);
		}
	}
	
	public static function importFrameWorkController($name = FALSE) {
		if($name) {
			$path = LIB.DS.'controller'.$name.DS.'controller.php';
			self::includeFile($path);
		}
	}
	
	public static function includeFile($file) {
		if(file_exists($file))
			require_once $file;
		else
			Throw new Exception ('Arquivo solicitado nao existe! Arquivo: '.$file,1);
	}
}
?>