<?php
require_once (APP_VENDOR.DS.'urbanairship_api'.DS.'urbanairship.php');
require_once (APP_MODEL.DS.'Pacote.php');
require_once (APP_MODEL.DS.'Evento.php');
require_once (APP_MODEL.DS.'Device.php');
require_once (APP_LIB.DS.'CorreiosWS.php');

class AppleController extends AppController{
	
	/**
	 * beforeFilter
	 */
	protected function beforeFilter() {
		//$this->_setTemplate('json',FALSE);
		$this->_setJsonContent();
		$this->_viewOptions['text'] = '';
	}
	
	
	/**
	 * registerDevice
	 */
	public function registerDevice($params = NULL) {
		$retorno = array('erro' => 1);
		if(!is_null($params) && is_array($params)) {
			$retorno['erro'] = 0;
			
			// Registrar aparelho
			$deviceToken = str_replace(" ", "", $params[0]);
			
			$mDevice = new DeviceModel('database_app');
			
			$device = $mDevice->getDeviceByToken($deviceToken);
			
			if(is_null($device)) {
				$salvou = $mDevice->saveDevice($deviceToken);
				if(!$salvou)
					$retorno['erro'] = 1;
			}

		}
		$this->_viewOptions['text'] = json_encode($retorno);
	}

	/**
	 * desativaDevice
	 */
	public function desativaDevice($params = NULL) {
		$retorno = array('erro' => 1);
		if(isset($params[0]) && !empty($params[0])) {
			$retorno['erro'] = 0;
			$deviceToken 	=& $params[0];
			
			$mDevice = new DeviceModel('database_app');
			$device = $mDevice->getDeviceByToken($deviceToken);
			
			if(is_null($device)) {
				$retorno['erro'] = 1;
				goto termina;
			}
			
			$mDevice->id = $device['id_device']; 
			$atualizou = $mDevice->desativaNotificacoes();
		}
		termina:
		$this->_viewOptions['text'] = json_encode($retorno);
	}
	
	/**
	 * desativaDevice
	 */
	public function ativaDevice($params = NULL) {
		$retorno = array('erro' => 1);
		if(isset($params[0]) && !empty($params[0])) {
			$retorno['erro'] = 0;
			$deviceToken 	=& $params[0];
			//$codigoRastreio =& $params[1];
			//$ciaId			=  isset($params[2]) ? $params[2] : 1;
			
			$mDevice = new DeviceModel('database_app');
			$device = $mDevice->getDeviceByToken($deviceToken);
			
			if(is_null($device))
				goto termina;
			
			$mDevice->id = $device['id_device']; 
			$atualizou = $mDevice->ativaNotificacoes();
		}
		termina:
		$this->_viewOptions['text'] = json_encode($retorno);
	}
//------------------------------------------------------------------------------------------------------------
	/**
	 * addEncomenda
	 */
	public function addEncomenda($params = NULL) {
		$retorno = array('erro' => 1);
		if($this->validaParamEncomenda($params)) {
			$retorno['erro'] = 0;
			$deviceToken 	=  str_replace(" ", "", $params[0]);
			$codigoRastreio =& $params[1];
			$ciaId			=  isset($params[2]) ? $params[2] : 1;
			
			$mDevice = new DeviceModel('database_app');
			$device = $mDevice->getDeviceByToken($deviceToken);
			
			if(is_null($device)) {
				$inseriu = $mDevice->saveDevice($deviceToken);
				
				if(!$inseriu) {
					$retorno['erro'] = 1;
					goto terminar;					
				}
			}				
			
			$deviceId = $mDevice->id;
			
			$mPacote = new PacoteModel('database_app');
			// Se o pacote ja estiver registrador, termina
			$pacote = $mPacote->getPacote($codigoRastreio,$deviceId);
			if(!is_null($pacote))
				goto terminar;
			
			$ciaId = 1;
			
			$inseriu = $mPacote->savePacote($deviceId,$deviceToken,$codigoRastreio,1,1);	
			if(!$inseriu) {
				$retorno['erro'] = 1;
				goto terminar;
			}
		}
		terminar:
		$this->_viewOptions['text'] = json_encode($retorno);
	}
	
	/**
	 *  validaParamEncomenda
	 */
	private function validaParamEncomenda($params = NULL) {
		if(is_null($params) || !is_array($params))
			return FALSE;
		else if(!isset($params[0]) || empty($params[0]))
			return FALSE;
		else if(!isset($params[1]) || empty($params[1]))
			return FALSE;
		
		return TRUE;
	}
	
	
	
	
	
	/**
	 * registraUrban
	 * Registra no urbanAirship
	 */
	public function registraUrban() {
		$retorno = array('erro' => 0);
		$dados = Conf::r('urbanairship');
		
		$mPacote = new PacoteModel('database_app');
		$mDevice = new DeviceModel('database_app');
		$mPacote->debug(TRUE);
		
		$pacotesResult = $mPacote->getPacotesResult("urbanairship=0", array('token','id_pacote'), 'token');

		if($mPacote->lines <= 0 || is_null($mPacote->lines))	
			goto termina;
		
		$airship = new Airship($dados['appkey'], $dados['secret']);
		
		while ($l = mysql_fetch_assoc($pacotesResult)) {
			$token =& $l['token'];
			$airship->register($token);
			
			//$atualizacao_pacote = array('urbanairship'	=>	1);
			//$mPacote->atualizaPacote($atualizacao_pacote,$token);
			$mDevice->registraDeviceUrban($token);
		}
				
		
		termina:
		$this->_viewOptions['text'] = json_encode($retorno);
	}
	
	/**
	 * getDevices
	 */
	public function getDevices() {
		$dados = Conf::r('urbanairship');
		
		$retorno = array();
		$airship = new Airship($dados['appkey'], $dados['secret']);
		
		$tokens = $airship->get_device_tokens();
		
		foreach ($tokens as $item) {
		    $retorno[] = $item;
		}
		
		$this->_viewOptions['text'] = json_encode($retorno);
	}
	
	
	/**
	 * requestCorreios
	 */
	public function requestCorreios() {

		// Correios WS
		$correio = new CorreiosWS();
		
		// Model
		$mPacote = new PacoteModel('database_app');
		$mPacote->debug(FALSE);
		
		$erro = array('erro' => 0);

		$result = $mPacote->getPacotesResult("recebe_notificacao=1 AND (finalizado != 1 OR ISNULL(finalizado) = 1) AND urbanairship=1", array('id_device','id_pacote','codigo','token','qtd_eventos'));
		
		if($mPacote->lines <= 0 )
			goto terminar;
		
		$mEvento = new EventoModel('database_app');
		$mEvento->debug(FALSE);
		
		
		while ($pacote = mysql_fetch_assoc($result))  {
			$data = $correio->getPacote($pacote['codigo']);

			if(isset($data['erro']))
				continue;
			
			$qtdEventos = count($data['objeto']['evento']);
			
			if($qtdEventos != $pacote['qtd_eventos'] && $qtdEventos > 0) {

				$ultimo_evento = isset($data['objeto']['evento'][0]) ? $data['objeto']['evento'][0] : $data['objeto']['evento'];
				
				$evento_salvar = array(
					'id_pacote'	=>	$pacote['id_pacote'],
					'data'		=>	isset($ultimo_evento['data']) 		? $ultimo_evento['data'] 		: '', // converter
					'hora'		=>	isset($ultimo_evento['hora']) 		? $ultimo_evento['hora'] 		: '',
					'descricao'	=>	isset($ultimo_evento['descricao']) 	? $ultimo_evento['descricao'] 	: '',
					'status'	=>	isset($ultimo_evento['descricao']) 	? $ultimo_evento['descricao'] 	: '',
					'local'		=>	isset($ultimo_evento['local']) 		? $ultimo_evento['local'] 		: '',
					'estado'	=>	isset($ultimo_evento['uf']) 		? $ultimo_evento['uf'] 			: '',
					'cidade'	=>	isset($ultimo_evento['cidade']) 	? $ultimo_evento['cidade'] 		: '',
				);
				
				$id_evento = $mEvento->idEventoByPacote($pacote['id_pacote']);
				
				if(is_null($id_evento)) {
					$mEvento->reset();
					$mEvento->save($evento_salvar);
				} else {
					$mEvento->atualizaEvento($evento_salvar,$pacote['id_pacote']);
				}
				// seta pacote para receber atualizacao
				
				$mPacote->id = $pacote['id_pacote'];
				
				$finalizado = $ultimo_evento['descricao'] == "Entregue" ? 1 : 0;
				
				$atualizacao_pacote = array(
					'qtd_eventos'			=>	$qtdEventos,
					'atualizado'			=>	1,
					'finalizado'			=>	$finalizado,
					'ultima_atualizacao'	=>	date("Y-m-d h:s"),
					'status_atual'			=>	$ultimo_evento['descricao'],
				);
				
				$mPacote->atualizaPacote($atualizacao_pacote);
			}
			
		}
		
		terminar:
		$this->_viewOptions['text'] = json_encode('finished');
	}
	
	
	
	/**
	 * verificaAtualizacoes
	 */
	public function verificaAtualizacoes() {
		$mPacote = new PacoteModel();
		$result = $mPacote->getPacotesResult("atualizado=1");
		
		while($l = mysql_fetch_assoc($result)) {
			
		}
		
	}
	
	public function push() {
		echo "<pre>";
		$this->_showView = FALSE;
		// Device tokens
		$deviceToken = 'a95c66953a7ecb6db08dedfd73aebd893b9765e8fa650565556d85a911075d2d';
		//$deviceToken = "6488acf68fe08d6cbe6c292e8f3d1c9d09be7b94fa2ce3d699a6c92ec6e96e19";
		// Senha
		$passphrase = 'LIlo.hass*911014*';

	 	define('APP_KEY','od3ipJ1-T8aOC6B9xs5JDg'); 
		define('PUSHSECRET', 'UA0VrDFXTJSqtEIg7Hgmnw'); // Master Secret
		define('PUSHURL', 'https://go.urbanairship.com/api/push/broadcast/');
		
		
		// Create Airship object
		$airship = new Airship(APP_KEY, PUSHSECRET);

		// Test feedback
		
		//$time = new DateTime('now', new DateTimeZone('UTC'));
		//$time->modify('-1 day');
		//echo $time->format('c') . '\n';
		//print_r($airship->feedback($time));
		
		// Test register
		
		//$airship->register($deviceToken, 'iphoneLilo');
		
		// Test get device token info
		//print_r($airship->get_device_token_info($TEST_DEVICE_TOKEN));
		
		// Test get device tokens
		
		/*$tokens = $airship->get_device_tokens();
		foreach ($tokens as $item) {
		    print_r($item);
		}*/
		
		// Test deregister
		
		//$airship->deregister($TEST_DEVICE_TOKEN);
		
		
		// Test push
		
		$message = array(
			'aps'	=>	array(
				'alert'=>'hello'
			)
		);
		$airship->push($message, $deviceToken);
		
		// Test broadcast
		
		//$broadcast_message = array('aps'=>array('alert'=>'hello to all'));
		//$airship->broadcast($broadcast_message, array($TEST_DEVICE_TOKEN));
	}
}
















