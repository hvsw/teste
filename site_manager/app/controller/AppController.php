<?php
require_once LIB . DS . 'Session.php';
class AppController extends AppBaseController{
	
	protected $useSession = false;
	protected $session = null;
	protected $_logger = NULL;
	protected $_template = 'default';
	
	public function __construct() {
		$this->_logger		= new Logger();
		$this->_logger->setFolder(WEB_PUBLIC.DS.'log');
		
		parent::__construct();
		
		if($this->useSession)
			$this->initSession();
		
	}
	
	private function initSession() {
		$this->session = Session::getSession();
	}
	
	public function readLog($params = NULL) {
		$this->cancelView();		
		$this->_logger->readLog($params);
	}
	
	public function listLogs() {
		$this->cancelView();
		$this->_logger->listLogs();
	}
	
	public function readAllLogs() {
		$this->cancelView();	
		$this->_logger->readAllLogs();
	}
	
	protected function beforeFilter() {
		
	}
	/*protected function afterFilter() {
		//echo "já executou a action...";
	}
	protected function beforeRender(){
		//echo "vai renderizar...";
	}
	protected function afterRender() {
		//echo "renderizou...";
	}*/
}
?>