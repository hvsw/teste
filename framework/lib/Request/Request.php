<?php
class Request {
	
	private $params = array();
	private $post_params = array();
	private $get_params = array();
	private $request_params = array();
	
	private $headers = array();	
	private static $_instance = null;
	
	public static function getRequest() {
		if(self::$_instance == null)
			self::$_instance = new Request();

		return self::$_instance;
	}

	private function __construct() {
		$this->params =& $_SERVER;
		$this->post_params 		=& $_POST;
		$this->get_params		=& $_GET;
		$this->request_params	=& $_REQUEST;
	}
	
	public function getPost() {
		return $this->post_params;
	}
	
	public function getGet() {
		return $this->get_params;
	}
	
	public function getPostValue($paramName) {
		if(isset($this->post_params[$paramName]))
			return $this->post_params[$paramName];
		return null;
	}
	
	public function getGetValue($paramName) {
		if(isset($this->get_params[$paramName]))
			return $this->get_params[$paramName];
		return null;
	}
	
	public function getValue($paramName) {
		if(isset($this->request_params[$paramName]))
			return $this->request_params[$paramName];
		return null;
	}
	
	public function getValues() {
		return $this->request_params;
	}
	
	public function getParams() {
		return $this->params;
	}
	
	public function getParam($param_name) {
		$param_name = strtoupper($param_name);
		if(isset($this->params[$param_name]))
			return $this->params[$param_name];

		return null;
	}
	
	public function isPost() {
		return strtolower($this->getParam("REQUEST_METHOD")) == 'post';
	}
	
	public function isGet() {
		return strtolower($this->getParam("REQUEST_METHOD")) == 'get';
	}
	
	public function requestMethod() {
		return strtolower($this->getParam("REQUEST_METHOD"));
	}
	
	public function getRequestIP() {
		return strtolower($this->getParam("REMOTE_ADDR"));
	}
	
	public function getRequestPort() {
		return strtolower($this->getParam("REMOTE_PORT"));
	}
	
	public function getRequestBrowser() {
		return strtolower($this->getParam("HTTP_USER_AGENT"));
	}
	
	public function getReferer() {
		return strtolower($this->getParam("HTTP_REFERER"));
	}
	
}