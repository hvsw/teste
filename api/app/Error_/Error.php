<?php
class Error extends AppBaseController{
	
	protected function beforeFilter() {
		$options['text'] = "";
		$this->setViewOptions($options);
		//$this->_setJsonContent();
	}
	
	protected function showError($error) {
		
		$retorno = array(
			'code'			=>	$error['error']->getCode(),
			'message'		=>	$error['error']->getMessage(),
			'http_status'	=>	'404 Not Found'
		);
		
		/*$this->_addHTTPHeader('Content-type: application/json');
		$this->_addHTTPHeader('X-Json: '.json_encode($error));
		$this->_addHTTPHeader('HTTP/1.1 404 Not Found');*/
		
		$this->_setViewContent(json_encode($retorno));
	}
}