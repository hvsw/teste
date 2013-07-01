<?php
class Response {
	
	private $params = array();
	private $headers = array();
	private static $_instance = null;
	
	private $errCodes = array(
	    // Informational 1xx
	    100 => 'Continue',
	    101 => 'Switching Protocols',
	
	    // Success 2xx
	    200 => 'OK',
	    201 => 'Created',
	    202 => 'Accepted',
	    203 => 'Non-Authoritative Information',
	    204 => 'No Content',
	    205 => 'Reset Content',
	    206 => 'Partial Content',
	
	    // Redirection 3xx
	    300 => 'Multiple Choices',
	    301 => 'Moved Permanently',
	    302 => 'Found',  // 1.1
	    303 => 'See Other',
	    304 => 'Not Modified',
	    305 => 'Use Proxy',
	    // 306 is deprecated but reserved
	    307 => 'Temporary Redirect',
	
	    // Client Error 4xx
	    400 => 'Bad Request',
	    401 => 'Unauthorized',
	    402 => 'Payment Required',
	    403 => 'Forbidden',
	    404 => 'Not Found',
	    405 => 'Method Not Allowed',
	    406 => 'Not Acceptable',
	    407 => 'Proxy Authentication Required',
	    408 => 'Request Timeout',
	    409 => 'Conflict',
	    410 => 'Gone',
	    411 => 'Length Required',
	    412 => 'Precondition Failed',
	    413 => 'Request Entity Too Large',
	    414 => 'Request-URI Too Long',
	    415 => 'Unsupported Media Type',
	    416 => 'Requested Range Not Satisfiable',
	    417 => 'Expectation Failed',
	
	    // Server Error 5xx
	    500 => 'Internal Server Error',
	    501 => 'Not Implemented',
	    502 => 'Bad Gateway',
	    503 => 'Service Unavailable',
	    504 => 'Gateway Timeout',
	    505 => 'HTTP Version Not Supported',
	    509 => 'Bandwidth Limit Exceeded'
	);
	
	public static function getResponse() {
		if(self::$_instance == null)
			self::$_instance = new Response();
		
		return self::$_instance;
	}
	
	private function __construct() {}
	
	public function setParam($name,$value) {
		if(!is_string($param) || !is_string($value))
			return;

		$this->params[$name] = $value;
	}
	
	public function getParams() {
		return $this->params;
	}
	
	public function setContentType($contentType) {
		$this->headers[] = "Content-type: " . $contentType;	
	}
	
	public function setHeader($header) {
		$this->headers[] = $header;
	}
	
	public function send($location,$sendParams = null) {
		if(!is_null($sendParams) && !empty($this->params))
			$location .= "?" . http_build_query($this->params);

		header('Location: ' . $location);
	}
	
	public function sendHeader($header) {
		header($header);
	}
	
	public function setHTTPError($errCode) {
		if(isset($this->errCodes[$errCode]))
			header($this->errCodes[$errCode], true, $errCode);
	}
	
	public function sendHeaders(){		
		foreach($this->headers as $k => $header)
			header($header);
		
	}
	
}