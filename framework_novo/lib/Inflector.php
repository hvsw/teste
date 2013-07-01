<?php
class Inflector {

	public static function controllerName($name) {
		return ucfirst(strtolower($name)).'Controller';
	}
	
	public static function makeURL($controller,$action,$params = NULL) {
		$first = strtolower(substr($controller, 0,-10));
		
		$url = $first."/".$action;
		
		if(!is_null($params) && is_array($params))
			$url = $url."/".implode("/", $params);
		else if(!is_nan($params) && is_string($params))
			$url = $url."/".$params;
		
		return $url;		
	}
	
	public static function urlController($controller) {
		return strtolower(substr($controller, 0,-10));
	}
	
	public static function modelName($name) {
		return ucwords($name)."Model";
	}
	
	public static function  methodName($name) {
		if(strstr($name,'_')) {
			$name = strtolower($name);
			$name = explode("_", $name);
			$first_name = $name[0];
			unset($name[0]);
			$name = array_map('ucfirst', $name);
			$name = $first_name.implode("", $name);
		}
		return $name;
			
	}
	
	public static function actionView($action) {
		return strtolower($action);
	}
	
	public static function cleanRequest($request) {
		if(substr($request,0,1) == '/')
			$request = substr($request,1,strlen($request));
		if(substr($request,-1) == '/')
			$request = substr($request,0,strlen($request)-1);
		return $request;
	}
}

?>