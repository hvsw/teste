<?php
class Error extends BaseController{
	
	protected function beforeFilter() {
		$options['file'] = LIB.DS.$this->_name.DS.'views'.DS.$this->_action.'.php';
		$this->setViewOptions($options);
		$this->_setTemplate('default',TRUE);
	}
	protected function showError($error) {
		$this->addVar('error_exception',$error['error']);
	}
}
?>