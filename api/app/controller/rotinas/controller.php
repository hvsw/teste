<?php
require_once (APP.DS.'model'.DS.'Pacote.php');
require_once (APP.DS.'model'.DS.'Evento.php');
require_once (APP.DS.'model'.DS.'Device.php');
require_once (APP.DS.'lib'.DS.'CorreiosWS.php');
require_once (APP.DS.'lib'.DS.'APNS.php');

class RotinasController extends AppController {
	
	/**
	 * afterFilter
	 */
	protected function afterFilter() {
		$this->response->setContentType('application/json');
	}
	
	/**
	 * sendPush
	 */
	public function sendPush() {
		
		$apns = new APNS(WEB_PUBLIC.DS.'files'.DS.'ck.pem');
		$apns->setDeviceToken("b6bcf2e86e739a025676b193e65a5e5e1ef255c4662ac4fcd14ad73256157028");
		$apns->setMessage("Ola Mundo!");

		$content = array();

		if($apns->sendPush(array('ola' => 'mundo'))) {
			$content['error'] = 0;
		} else {
			$content = $apns->getError();
		}

		$this->view->setContent(json_encode($content));
	}
	 
	/**
	 * requestCorreios
	 */
	public function requestCorreios() {
		$this->cancelView();

		$correio = new CorreiosWS();
				
		$mPacote = new PacoteModel('database_app');
		$mPacote->debug(false);
		
		$result = $mPacote->getPacotesAtivosResult();

		if($mPacote->lines > 0 ) {
			
			while ($pacote = mysql_fetch_assoc($result))  {

				$pacoteCorreio = $correio->getPacote($pacote['codigo']);
					
				if($correio->getErro()) {
					$this->_logger->log('requestcorreios_erros',json_encode(array('pacote_banco' => $pacote, 'retorno_correios' => $pacoteCorreio)));
					continue;
				}
				
				if(isset($pacoteCorreio->objeto->evento->tipo)) {
					
					$ultimo_evento = (array)$pacoteCorreio->objeto->evento;
					$ultimo_evento['descricao'] = utf8_decode($ultimo_evento['descricao']);
					
					if(!empty($ultimo_evento['descricao']) && $ultimo_evento['descricao'] != $pacote['status_atual']) {
						
						$mPacote->id	= $pacote['id_pacote'];
						$finalizado 	= $ultimo_evento['descricao'] == "Entregue" ? 1 : 0;
						
						$_data_cadastro = explode(" ", $pacote['data_cadastro']);
						$data_cadastro 	= new DateTime($_data_cadastro[0]);
						$data_evento	= new DateTime($this->formataData($ultimo_evento['data']));
						
						$atualizado = $data_cadastro > $data_evento ? 0 : 1;
						
						$atualizacao_pacote = array(
							'atualizado'			=>	$atualizado,
							'finalizado'			=>	$finalizado,
							'ultima_atualizacao'	=>	date("Y-m-d h:s"),
							'status_atual'			=>	$ultimo_evento['descricao'],
						);

						$mPacote->atualizaPacote($atualizacao_pacote);
							
					}
				}
				
			}
			
		}
		$this->_logger->log('requestcorreios','FIM: '.date("d/m/Y h:m:i"));
	}
	
	
	
	/**
	 * verificaAtualizacoes
	 */
	public function notificar() {
		$this->cancelView();

		$mPacote = new PacoteModel('database_app');
		$apns 	 = new APNS(WEB_PUBLIC_PATH.DS.'files'.DS.'ck.pem');
		$result  = $mPacote->getPacotesPush();
		
		while($l = mysql_fetch_assoc($result)) {

			$deviceToken 	= $l['token'];
			$mensagem 		= "Sua encomenda ".strtoupper($l['codigo'])." recebeu atualizações! O novo status é: ".$l['status_atual'];

			$apns->setMessage($mensagem);
			$apns->setDeviceToken($deviceToken);

			$extraContent = array('cod_rastreio' => $l['codigo']);

			if($apns->sendPush($extraContent)) {

				$mPacote = new PacoteModel('database_app');
				$mPacote->id = $l['id_pacote'];
				
				$atualizacao = array('atualizado' => 0);
				$mPacote->atualizaPacote($atualizacao);

			} else {
				$this->_logger->log('notificar_erros',json_encode($apns->getError()));	
			}
			
		}
		$this->_logger->log('notificar','FIM: '.date("d/m/Y h:m:i"));
	}
	
	private function retira_acentos($texto){
 		return strtr($texto, "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ", "aaaaeeiooouucAAAAEEIOOOUUC");
	}

	private function formataData($data) {
		$spl_data = explode("/", $data);
		
		$data_nova = $spl_data[2]."-".$spl_data[1]."-".$spl_data[0]; 
		return $data_nova;		
	}
}