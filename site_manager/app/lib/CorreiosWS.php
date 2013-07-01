<?php
require_once (APP_VENDOR.DS.'XML'.DS.'Unserializer.php');
class CorreiosWS {
	
	private $erro = false;
	
	/**
	 * requestCorreios
	 */
	public function getPacote($cod_rastreio) {

		
		// Seta configuracoes
		$correiosConfs 	= Conf::r('correios');
		
		$postData = array(
			'Usuario'	=>	$correiosConfs['user'],
			'Senha'		=>	$correiosConfs['pass'],
			'Tipo'		=>	'L', // Lista de objetos - F = Intervalor de objetos
			'Resultado'	=>	'U',
		);
		
		// Pega token
		$postData['Objetos'] = strtoupper($cod_rastreio);
		
		// Faz request webservice
		$retorno 	= $this->doRequest($correiosConfs['url'], $postData);
		
		// Unparse
		if(empty($retorno))
			return null;

		// Pega array do xml
		//$parser			= new XML_Unserializer(array('complexType' => 'array'));
		//$parser->unserialize($retorno);
		//$data = $parser->getUnserializedData();
		
		$objeto = simplexml_load_string($retorno);
		$this->erro = $this->existeErro($objeto);
		
		return $objeto;
	}
	 
	/**
	 * getErro
	 */
	public function getErro() {
		return $this->erro;
	}
	
	
	/**
	 * existeErro
	 */ 
	public function existeErro($data) {
		if(is_null($data) || isset($data->erro))
			return true;
		else
			return false;
	}
	
	
	/**
	 * doRequest
	 */
	private function doRequest($url,$postData) {
		
		$strPost = http_build_query($postData);
		
		$tuCurl = curl_init(); 
		curl_setopt($tuCurl, CURLOPT_URL, $url);
		curl_setopt($tuCurl, CURLOPT_POST, 1);
		curl_setopt($tuCurl, CURLOPT_POSTFIELDS, $strPost);
		curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1);
		
		$tuData = curl_exec($tuCurl);
		curl_close($tuCurl);
		
		return $tuData;
	}

}
