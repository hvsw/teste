<?php
class Session {
	
	private $params = array();
	private $id;
	
	private static $_instance = null;
	
	public static function getSession() {
		if(self::$_instance == null){
			self::$_instance = new Session();
		}

		return self::$_instance;
	}

	private function __construct() {
		if(session_id() == "")
				session_start();
		
		$this->id = session_id();
		$this->params =& $_SESSION;
	}
	
	public function close() {
		session_destroy();
		$this->id = null;
	}
	
	public function remove($name) {
		if(isset($this->params[$name]))
			session_unset($name);
	}
	
	public function setParam($name,$value) {
		$this->params[$name] = $value;
	}
	
	public function getParams() {
		return $this->params;
	}
	
	private function getParam($param_name) {
		if(isset($this->params[$param_name]))
			return $this->params[$param_name];

		return null;
	}
	
}