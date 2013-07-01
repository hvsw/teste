<?php
require_once LIB . DS . 'Session.php';

class AppController extends AppBaseController{
	
	protected $useSession = false;
	protected $session = null;
	protected $_logger = NULL;
	
	public function __construct() {
		$this->_logger		= new Logger();
		$this->_logger->setFolder(WEB_PUBLIC.DS.'log');
		
		parent::__construct();
		
		if($this->useSession)
			$this->initSession();
		
	}
	
	public function teste() {
	}
	
	private function initSession() {
		$this->session = Session::getSession();
	}
	
	public function readLog($params = NULL) {		
		$this->cancelView();
		$filename = $params;
		
		$file = fopen(WEB_PUBLIC.DS.'log'.DS.$filename, 'r') or die('Arquivo nao existe');
		while ($l = fgets($file)) {
			printr(json_decode($l));
			echo "<br>";
		}
		fclose($file);
	}
	
	public function listLogs() {
		
		if(is_dir(WEB_PUBLIC.DS.'log')) {
			$dir = opendir(WEB_PUBLIC.DS.'log');
		
			$viewContent = "";
			$url = "http://" . $this->request->getParam("server_name") . "/index/readlog/";
			while($filename = readdir($dir)) {
				
				if($filename == "." || $filename == ".." )
					continue;
				
				$viewContent .= "<a href='" . $url . $filename . "' target='_blank'>".$filename."</a><br>";
			}
			closedir($dir);
			$this->view->setContent($viewContent);
		} else {
			$this->view->setContent("A pasta " . WEB_PUBLIC.DS.'log' . " nao existe");
		}
	}
	
	public function readAllLogs() {
		
		$dir = opendir(WEB_PUBLIC.DS.'log');
		while($filename = readdir($dir)) {
			
			if($filename == "." || $filename == ".." )
				continue;
			
			$file = fopen(WEB_PUBLIC.DS.'log'.DS.$filename, 'r');
			echo "<h1>START ".$filename."</h1>";
			
			while ($l = fgets($file)) {
				printr(json_decode($l));
				echo "<br>";
			}
			
			echo "<h1>END ".$filename."</h1>";
			
			fclose($file);
		}
		closedir($dir);
	}
	
	
	/*protected function beforeFilter() {
		//echo "vai chamar a action...";
	}
	protected function afterFilter() {
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