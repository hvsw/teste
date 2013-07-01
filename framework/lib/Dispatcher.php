<?php
class Dispatcher {

	private $request;
	private $defaultAction;
	private $defaultController;
	
	public function __construct() {
		$this->defaultAction 		= 'default';
		$this->defaultController 	= 'default';
	}
	
	public function __get($p) {
		return isset($this->{$p}) ? $this->{$p} : FALSE;
	}
	
	public function setDefaultAction($action) {
		$this->defaultAction = $action;
	}
	
	public function setDefaultController($ctr) {
		$this->defaultController = $ctr;
	}
	
	public function getDefaultController() {
		return $this->defaultController;
	}
	
	public function getDefaultAction() {
		return $this->defaultAction;
	}
	
	public function dispatch() {

		$this->request 		= Routes::getRoute($_SERVER['REQUEST_URI']);
		$stripped_request 	= $this->stripRequest($this->request);
		
		$controllerName = isset($stripped_request[0]) ? $this->getControllerName($stripped_request[0]) : $this->defaultController;
		$action			= isset($stripped_request[1]) ? Inflector::methodName($stripped_request[1]) : $this->defaultAction;
		
		$params			= $this->getParams($this->request);

		try{
			$controller = $this->instance($controllerName);
			$controller->setDir(APP.DS.'controller'.DS.$controllerName);
			$controller->process($action,$params);

		} catch (Exception $e) {
			
			die($e->getMessage());
		}
		
	}
	
	private function instance($controllerName) {
		
		$file 		= APP . DS . 'controller' . DS . strtolower($controllerName) . DS . 'controller.php';
		$className	= Inflector::controllerName($controllerName);
		
		if(!file_exists($file))
			Throw new Exception ('O arquivo '.$controllerName.' nao existe',1);
		else
			require_once $file;
		
		
		if(!class_exists($className))
			Throw new Exception ('A classe ' . $className . ' nao existe',1);
		else
			return new $className();
	}
	
	private function getControllerName($request) {
		$arr_request = $this->stripRequest($request);
		
		if(!isset($arr_request[0]) || $arr_request[0] == '')
			Throw new Exception ('Nenhuma classe encontrada na requisicao. Arquivo:'.__FILE__.' - Linha: '.__LINE__,10);
			
		return $arr_request[0];
	}
	
	private function getParams($request) {
		
		if(!empty($_GET))
			$request = str_replace("?".http_build_query($_GET), "", $request);
		
		$arr_request = $this->stripRequest($request);
		
		$return = NULL;
		if(isset($arr_request[2]) && count($arr_request) > 2) {
			$params = array_shift($arr_request);
			$params = array_shift($arr_request);
			
			$return = $arr_request;
		}
		
		return $return;
		
	}
	
	private function stripRequest($request) {
		$request = Inflector::cleanRequest($request);
		return explode('/',$request);
	}
	
}