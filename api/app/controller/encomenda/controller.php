<?php

require_once (APP.DS.'model'.DS.'Pacote.php');
require_once (APP.DS.'model'.DS.'Evento.php');
require_once (APP.DS.'model'.DS.'Device.php');

class EncomendaController extends AppController{

	/**
	 * afterFilter
	 */
	protected function afterFilter() {
		$this->response->setContentType('application/json');
	}

	/**
	 * addEncomenda
	 * enviar: /token/codrastreio/ciaid
	 */
	public function addEncomenda($deviceToken,$codigoRastreio, $ciaId =1) {
		$retorno = array('erro' => 0);
		
		$mDevice = new DeviceModel('database_app');
		$device = $mDevice->getDeviceByToken($deviceToken);
		$inseriu = true;
		if(is_null($device))
			$inseriu = $mDevice->saveDevice($deviceToken);
		
		if(!$inseriu)
			$retorno['erro'] = 1;
		else {
			$deviceId = $mDevice->id;
			$mDevice->atualizaAcesso();
			
			$mPacote = new PacoteModel('database_app');
			
			$pacote = $mPacote->getPacote($codigoRastreio,$deviceId, $ciaId);
			
			if(is_null($pacote)) {
				$ciaId = 1;
				
				$inseriu = $mPacote->savePacote($deviceId,$codigoRastreio,1,1);	
				if(!$inseriu) {
					$retorno['erro'] = 1;
				}
			} else {
				$ativou = $mPacote->ativaPacote($pacote['id_pacote']);
				if(!$ativou)
					$retorno['erro'] = 1;
			}
		}
		
		$this->_logger->log('addencomenda',json_encode(array('token' => $device, 'cod_rastreio' => $codigoRastreio, 'ciaId' => $ciaId)));
		$this->view->setContent(json_encode($retorno));
	}
	
	/**
	 * ativaEncomenda
	 */
	public function ativaEncomenda($deviceToken,$codigoRastreio, $ciaId =1) {
		$retorno = array('erro' => 0);

		$mDevice = new DeviceModel('database_app');
		$device  = $mDevice->getDeviceByToken($deviceToken);
		
		$inseriu = true;
		if(is_null($device))
			$inseriu = $mDevice->saveDevice($deviceToken);
		
		if(!$inseriu) {
			$retorno['erro'] = 1;
		} else {
			$deviceId = $mDevice->id;
			$mDevice->atualizaAcesso();

			$mPacote = new PacoteModel('database_app');
			$pacote	 = $mPacote->getPacote($codigoRastreio,$deviceId,$ciaId);

			if(!is_null($pacote)) {
				$ciaId = 1;

				$ativou = $mPacote->ativaPacote($pacote['id_pacote']);
				if(!$ativou)
					$retorno['erro'] = 1;
			}
		}

		$this->_logger->log('ativaencomenda',json_encode(array('token' => $device, 'cod_rastreio' => $codigoRastreio, 'ciaId' => $ciaId)));
		$this->view->setContent(json_encode($retorno));
	}
	
	/**
	 * desativaEncomenda
	 */
	public function desativaEncomenda($deviceToken,$codigoRastreio, $ciaId =1) {
		$retorno = array('erro' => 0);
		
		$mDevice = new DeviceModel('database_app');
		$device = $mDevice->getDeviceByToken($deviceToken);
		
		$inseriu = true;
		if(is_null($device))
			$inseriu = $mDevice->saveDevice($deviceToken);
		
		if(!$inseriu) {
			$retorno['erro'] = 1;
		} else {
			$deviceId = $mDevice->id;
			$mDevice->atualizaAcesso();
			
			$mPacote = new PacoteModel('database_app');
			
			$pacote = $mPacote->getPacote($codigoRastreio,$deviceId,$ciaId);
			
			if(!is_null($pacote)) {
				$ciaId = 1;
				
				$ativou = $mPacote->desativaPacote($pacote['id_pacote']);
				if(!$ativou)
					$retorno['erro'] = 1;
			}
			
		}

		
		$this->_logger->log('desativaencomenda',json_encode(array('token' => $device, 'cod_rastreio' => $codigoRastreio, 'ciaId' => $ciaId)));
		$this->view->setContent(json_encode($retorno));
	}
	
	
	/**
	 * deleteEncomenda
	 */
	public function deleteEncomenda($params = NULL) {
		$retorno = array('erro' => 1);
		if($this->validaParamEncomenda($params)) {
			$retorno['erro'] = 0;
			$deviceToken 	=  str_replace(" ", "", $params[0]);
			$codigoRastreio =& $params[1];
			$ciaId			=  isset($params[2]) ? $params[2] : 1;
			
			$mDevice = new DeviceModel('database_app');
			$device = $mDevice->getDeviceByToken($deviceToken);
			
			if(is_null($device)) {
				$retorno['erro'] = 1;
			} else {
				$deviceId 	= $mDevice->id;
				$mDevice->atualizaAcesso();
				
				$mPacote	= new PacoteModel('database_app');
				$pacote 	= $mPacote->getPacote($codigoRastreio,$deviceId,$ciaId);
				
				if(!is_null($pacote)) {
					$excluiu = $mPacote->deletePacote($pacote['id_pacote']);
					if(!$excluiu)
						$retorno['erro'] = 1;
				}
			}
			
		}
		$this->_logger->log('deleteencomenda',json_encode(array('params'=>$params)));
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
	
}
















