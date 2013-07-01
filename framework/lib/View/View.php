<?php
class View {
		
	private $_vars 	= array();
	private $_css	= array();
	private $_js	= array();
	private $controller;
	private $dir;
	private $viewName;
	private $content;
	private $options;
	
	public function __construct($controller = NULL) {
		if(!is_null($controller))
			$this->controller =& $controller; 
	}
	
	public function set($name,$value) {
		$this->_vars[$name] = $value;
		
		return $this;
	}
	
	public function remove($name) {
		unset($this->_vars[$name]);
	}
	
	public function setContent($content) {
		$this->options['text'] = $content;
	}
	
	public function setViewDir($dir) {
		$this->dir = $dir;
	}
	
	public function setView($viewName) {
		$this->viewName = $viewName;	
	}
	
	public function setVars($vars) {
		if(is_array($vars))
			$this->_vars += $vars;
		else
			$this->_vars[] = $vars;
	}
	
	public function setJs($js) {
		if(is_array($js))
			$this->_js += $js;
		else
			$this->_js[] = $js;
		
		return $this;
	}
	
	public function setCss($css) {
		if(is_array($css))
			$this->_css += $css;
		else
			$this->_css[] = $css;
		
		return $this; 
	}
	
	public function getCss() {
		return $this->_css;
	}
	
	public function getJs() {
		return $this->_js;
	}
	
	public function css() {
		if(!empty($this->_css)) {
			foreach($this->_css as $css) {
				echo "<link rel=\"stylesheet\" href=\"/css/{$css}.css\" />";
			}
		}
	}
	
	public function js() {
		if(!empty($this->_js)) {
			foreach($this->_js as $js) {
				echo "<script type=\"text/javascript\" src=\"/js/{$js}.js\"></script>";
			}
		}
	}
	
	public function renderFile($file) {
		if(!file_exists($file))
			Throw new Exception('Arquivo de view '.$file.' para renderizar n&atilde;o existente',0);
		
		extract($this->_vars);
		
		ob_start();
		include $file;
		return ob_get_clean();
	}
	
	public function render($options = NULL) {
		if(is_null($options))
			$options = $this->options;
		
		if(isset($options['text']))
			$this->content = $options['text'];
		else
			$this->content = $this->renderFile($this->dir . DS . $this->viewName . '.php');
		
		return $this->content;
	}
}
?>