<?php
class WebserviceController extends AppController{
	protected function beforeFilter() {
		parent::beforeFilter();
		//$this->_setTemplate('json');
		$this->cancelView();
	}
	public function index($params = array() ) {
		//$this->curlRequest();
		$array_nomes = array('william','marques','hass');
		//echo '["teste1"]';
		echo json_encode($array_nomes);
		
	}
	
	public function info() {
		phpinfo();
		die();
	}
	
	public function ports() {
		$host = 'stackoverflow.com';
		$ports = array(2195);
		
		foreach ($ports as $port)
		{
		    $connection = @fsockopen($host, $port);
		
		    if (is_resource($connection))
		    {
		        echo '<h2>' . $host . ':' . $port . ' ' . '(' . getservbyport($port, 'tcp') . ') is open.</h2>' . "\n";
		
		        fclose($connection);
		    }
		
		    else
		    {
		        echo '<h2>' . $host . ':' . $port . ' is not responding.</h2>' . "\n";
		    }
		}
	}
	
	public function push() {
		error_reporting(E_ALL);	

		$device = "6488acf68fe08d6cbe6c292e8f3d1c9d09be7b94fa2ce3d699a6c92ec6e96e19";
		$pass	= "LIlo.hass*911014*";
		$message = "Teste de mensagem push!";
		
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', WEB_PUBLIC_PATH.DS.'files'.DS.'ck.pem');
		stream_context_set_option($ctx, 'ssl', 'passphrase', $pass);
		
		// Open a connection to the APNS server
		$fp = stream_socket_client(
			'ssl://gateway.sandbox.push.apple.com:2195', $err,
			$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		

		
		if (!$fp)
			exit("Failed to connect: $err $errstr" . PHP_EOL);
		
		
		echo 'Connected to APNS' . PHP_EOL;
		
		// Create the payload body
		$body = array (
			'aps' => array(
				'alert' 	=> 	$message,
				'sound'		=>	'default',
				'encomenda'	=>	'CKPSDJNASDJ',
			)
		);
		// Encode the payload as JSON
		$payload = json_encode($body);
		
		// Build the binary notification
		$msg = chr(0) . pack('n', 32) . pack('H*', $device) . pack('n', strlen($payload)) . $payload;
		
		// Send it to the server
		$result = fwrite($fp, $msg, strlen($msg));
		
		if (!$result)
			echo 'Message not delivered' . PHP_EOL;
		else
			echo 'Message successfully delivered' . PHP_EOL;
		
		// Close the connection to the server
		fclose($fp);
	}
	
	public function upload() {
		if(isset($_FILES['imagem'])) {
			move_uploaded_file($_FILES['imagem']['tmp_name'], WEB_PUBLIC_PATH.DS.'uploads'.DS.$_FILES['imagem']['name']);
		}
	}
	protected function curlRequest() {
			
		$tuCurl = curl_init(); 
		curl_setopt($tuCurl, CURLOPT_URL, "http://www.google.com.br/search?q=7896015516833");  
		curl_setopt($tuCurl, CURLOPT_VERBOSE, 0); 
		curl_setopt($tuCurl, CURLOPT_HEADER, 1);    
		curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1);  
		curl_setopt($tuCurl, CURLOPT_HTTPHEADER, 
					array("Content-Type: text",)
					); 
		
		$tuData = curl_exec($tuCurl);
		curl_close($tuCurl); 
		$arr_data = explode("<span class=\"st\">", $tuData);
		$new_data = explode("<b>", $arr_data[1]);
		$new_data2 = explode("<b>", $arr_data[2]);
		$new_data3 = explode("<b>", $arr_data[3]);
		$new_data4 = explode("<b>", $arr_data[4]);
		
		echo $new_data[0]."<br>";
		echo $new_data2[0]."<br>";
		echo $new_data3[0]."<br>";
		echo $new_data4[0]."<br>";
	}
	public function receivePost() {
		//echo "ola mundo!";
		print_r($_POST);
	}
	protected function afterFilter() {
		parent::afterFilter();
	}
	protected function afterRender() {
		parent::afterRender();
	}
}
?>