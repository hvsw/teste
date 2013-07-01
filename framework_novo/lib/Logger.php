<?php
class Logger {
	
	private $folder = NULL;
	
	private $appendContent = TRUE;
	
	public function log($file_name, $content = NULL) {
		$this->createFolder();
		
		if($this->appendContent)
			$content = json_encode($this->appendConent($content));
		
		$file = fopen($this->folder. DS . $file_name, "a+");

		fwrite($file, "\n".$content);
		
		fclose($file);
	}
	
	public function readLog($params = NULL) {
		$filename = $params;
		
		$file = fopen($this->folder.DS.$filename, 'r') or die('Arquivo nao existe');
		while ($l = fgets($file)) {
			printr(json_decode($l));
			echo "<br>";
		}
		fclose($file);
	}
	
	public function listLogs() {
		$dir = opendir($this->folder);
		
		$viewContent = "";
		$url = "http://" . $_SERVER["SERVER_NAME"] . "/index/readlog/";
		while($filename = readdir($dir)) {
			
			if($filename == "." || $filename == ".." )
				continue;
			
			$viewContent .= "<a href='" . $url . $filename . "' target='_blank'>".$filename."</a><br>";
		}
		closedir($dir);
		echo $viewContent;
	}
	
	public function readAllLogs() {
		
		$dir = opendir($this->folder);
		while($filename = readdir($dir)) {
			
			if($filename == "." || $filename == ".." )
				continue;
			
			$file = fopen($this->folder.DS.$filename, 'r');
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
	
	
	private function createFolder() {
		if(!is_dir($this->folder)) {
			mkdir($this->folder);
		}
	}
	
	private function setAppend($permission = FALSE) {
		$this->appendContent = $permission;
	}
	
	public function setFolder($path) {
		$this->folder = $path;
	}
	
	private function appendConent($content) {
		$browser 	= $_SERVER['HTTP_USER_AGENT'];
		$ip 		= $_SERVER['REMOTE_ADDR'];
		$method		= $_SERVER['REQUEST_METHOD'];
		$query_str	= $_SERVER['QUERY_STRING'];
		$url		= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];;
		
		$retorno = array(
			'content'	=>	$content,
			'browser'	=>	$browser,
			'ip'		=>	$ip,
			'method'	=>	$method,
			'query_str'	=>	$query_str,
			'url'		=>	$url,
			'data'		=>	date("d/m/Y h:i:s"),
			'log_id'	=>	microtime(), 
		);
		
		return $retorno;
	}
}
