<?php
abstract class BaseController {
	
	protected $_name 		= NULL;
	protected $_controller	= NULL;
	protected $view			= NULL;
	protected $model		= NULL;
	protected $_viewName	= NULL;
	protected $_action		= NULL;
	protected $_params		= NULL;
	protected $_dir			= NULL;
			
	protected $_template		= NULL;
	private $_showView		= TRUE;
	
	protected $session		= NULL;
	
	protected $request		= NULL;
	protected $response		= NULL;
	
	public function __construct() {

		$this->session		=& $_SESSION;

		$this->_name 		= get_class($this);
		$this->_controller	= Inflector::urlController($this->_name);
		$this->request		= Request::getRequest();
		$this->response		= Response::getResponse();
	}
	
	public function setDir($dir) {
		$this->_dir = $dir;
	}
	
	public function __get($param) {
		return isset($this->{$param}) ? $this->{$param} : NULL; 
	}
	
	public function process($action,$params = NULL) {
		
		if(is_null($action))
			Throw new Exception ('Action nula, nao pôde chamar o metodo',404);

		if(!method_exists($this,$action))
			Throw new Exception ('Metodo '.$action.' inexistente na controller '.$this->_controller,404);

		if(!is_callable(array($this,$action)))
			Throw new Exception ('Metodo '.$action.' nao pode ser chamado na controller '.$this->_controller,404);
		
		$this->beforeFilter();

		$this->_params = $params;
		$this->_action = $action;
		
		$this->loadView();
		
		
		
		if(!is_null($this->_params))
			call_user_func_array(array($this, $this->_action), $this->_params);
		else
			call_user_func(array($this, $this->_action));
		
		$this->afterFilter();
				
		$this->beforeRender();
		
		$this->response->sendHeaders();
		
		if($this->_showView) {
			if($this->_template != null) {
				$content = $this->view->render();
				$tpl = new View($this);
				$tpl->setView($this->_template);
				$tpl->setViewDir(APP.DS.'Templates');
				$tpl->setCss($this->view->getCss());
				$tpl->setJs($this->view->getJs());
				$tpl->set('template_content',$content);
				echo $tpl->render();
				
			} else {
				echo $this->view->render();	
			}
		}
		
		$this->afterRender();
	}
	
	private function loadModel() {
		$model_file = APP . DS . 'model' . DS . ucwords($this->_controller) . ".php";
		if(file_exists($model_file)) {
			$class_name = Inflector::modelName($this->_controller);
			if(class_exists($class_name))
				$this->model = new $class_name();
		}
	}
	
	private function loadView() {
		$this->view	= new View($this);
		$this->view->setView($this->_action);
		$this->view->setViewDir($this->_dir.DS.'view');
	}
	
	public function cancelView() {
		$this->_showView = FALSE;
	}
	
	public function allowView() {
		$this->_showView = TRUE;
	}
	
	protected function beforeFilter(){}
	protected function afterFilter() {}
	protected function beforeRender() {}
	protected function afterRender() {}
}
?>