<?php
require_once (APP.DS.'model'.DS.'Pacote.php');
require_once (APP.DS.'model'.DS.'Evento.php');
require_once (APP.DS.'model'.DS.'Device.php');
require_once (APP.DS.'lib'.DS.'CorreiosWS.php');

class DeviceController extends AppController{
	

	/**
	 * afterFilter
	 */
	protected function afterFilter() {
		$this->response->setContentType('application/json');
	}
	
	
	/**
	 * registerDevice
	 */
	public function registerDevice($deviceToken = null) {
		$retorno = array('erro' => 1);
		
		if(!is_null($deviceToken) && isset($deviceToken)) {
			$retorno['erro'] = 0;
			
			$deviceToken = str_replace(" ", "", $deviceToken);
			
			$mDevice = new DeviceModel('database_app');
			
			$device = $mDevice->getDeviceByToken($deviceToken);
			
			if(is_null($device)) {
				$salvou = $mDevice->saveDevice($deviceToken);
				if(!$salvou)
					$retorno['erro'] = 1;
			} else {
				$mDevice->atualizaAcesso();
			}
		}
		$this->_logger->log('registerdevice',$deviceToken);

		$this->view->setContent(json_encode($retorno));
	}

	/**
	 * desativaDevice
	 */
	public function desativaDevice($deviceToken) {
		$retorno = array('erro' => 1);
		if(isset($deviceToken)) {
			$retorno['erro'] = 0;
			
			$mDevice = new DeviceModel('database_app');
			$device = $mDevice->getDeviceByToken($deviceToken);
			
			if(!is_null($device)) {
				$mDevice->id 	= $device['id_device']; 
				$atualizou 		= $mDevice->desativaNotificacoes();
			} else {
				$retorno['erro'] = 1;
			}
			
		}
		$this->_logger->log('desativadevice',$deviceToken);
		$this->view->setContent(json_encode($retorno));
	}
	
	/**
	 * ativaNotificacao
	 */
	public function ativaDevice($deviceToken) {
		$retorno = array('erro' => 1);

		if(isset($deviceToken)) {

			$retorno['erro'] = 0;
			
			$mDevice = new DeviceModel('database_app');
			$device = $mDevice->getDeviceByToken($deviceToken);
			
			if(!is_null($device)) {
				$mDevice->id = $device['id_device']; 
				$atualizou = $mDevice->ativaNotificacoes();
			}
			
		}
		$this->_logger->log('ativadevice',$deviceToken);

		$this->view->setContent(json_encode($retorno));
	}

}
















