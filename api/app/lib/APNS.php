<?php
class APNS {
	
	private $deviceToken;
	private $passphrase;
	private $certificatePath;
	private $message;
	private $error = array();
	private $sound;
	
	const address = "ssl://gateway.sandbox.push.apple.com:2195";
	const defaultSound = "default";

	public function __construct($certificatePath) {
		$this->passphrase 		= 'LIlo.hass*911014*';
		$this->certificatePath	= $certificatePath;
		$this->sound 			= APNS::defaultSound;
	}
	
	public function setDeviceToken($token) {
		$this->deviceToken = $token;
	}
	
	public function getToken() {
		return $this->deviceToken;
	}
	
	public function setPassphrase($pass) {
		$this->passphrase = $pass;
	}
	
	public function getPassphrase() {
		return $this->passphrase;
	}
	
	public function setCertificatePath($path) {
		$this->certificatePath = $path;
	}
	
	public function getCertificatePath() {
		return $this->certificatePath;
	}
	
	public function setMessage($message) {
		$this->message = $message;
	}
	
	public function getMessage() {
		return $this->message;
	}
	
	public function setSound($sound) {
		$this->sound = $sound;
	}
	
	public function getSound() {
		return $this->sound;
	}
	
	public function getError() {
		return $this->error;
	}
	
	public function success() {
		return empty($this->error);
	}
	
	public function sendPush($extraContent = null) {
		
		if(!file_exists($this->certificatePath)) {
			$this->error['error'] 		= true;
			$this->error['strError'] 	= "File doesn't exists";
			return false;
		}
			
		
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', $this->certificatePath);
		stream_context_set_option($ctx, 'ssl', 'passphrase', $this->passphrase);
		
		$fp = stream_socket_client(
			APNS::address, $this->error['error'],
			$this->error['strError'], 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		
		if (!$fp)
			return false;
		
		$body['aps'] = array(
			'alert' => $this->message,
			'sound' => $this->sound,
		);
		
		if(!is_null($extraContent) || !empty($extraContent))
			$body['aps'] = $body['aps'] + $extraContent;
		
		$payload = json_encode($body);
		
		
		$msg = chr(0) . pack('n', 32) . pack('H*', $this->deviceToken) . pack('n', strlen($payload)) . $payload;

		$result = fwrite($fp, $msg, strlen($msg));
		
		fclose($fp);
		if (!$result) {
			$this->error['error'] 		= true;
			$this->error['strError'] 	= 'Message not delivered' . PHP_EOL;
			return false;			
		}
		else
			return true;
		
		
	}
	
	
}
