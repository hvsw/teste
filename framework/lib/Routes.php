<?php
class Routes {

	private static $routes;

	public static function getRoute($uri) {
		return isset(self::$routes[$uri]) ? self::$routes[$uri] : $uri;
	}
	
	public static function setRoute($route) {
		self::$routes = $route;
	}
}
?>