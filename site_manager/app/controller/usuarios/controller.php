<?php
class UsuariosController extends AppController
{

	protected $useSession = true;
	
	public function index () {
		
		$this->view->setView('login');
		$this->login();
	}

	public function login() {
		$this->_template = 'login';
		$form = $this->getForm();
		
		if($this->request->isPost()) {
			
			$usuario 	= $this->request->getPostValue("usuario");
			$senha		= $this->request->getPostValue("senha");
			
		}
		
		$this->view->set('form', $form);
	}
	
	public function logar() {
		printrx($this->request->getPost());
	}
	
	private function getForm() {
		$form = new Form('/usuarios/login');
		
		$form -> createElement('text', 'usuario')
	  		  -> attr('size','10');
		
		$form -> createElement('password', 'senha')
	  		  -> attr('size','10');
			  
		$form -> createElement('submit', 'enviar')
	  		  -> setValue('Enviar');
		
		$form -> createElement('textarea', 'texto')
	  		  -> setValue('texto texto');
		
		$form -> createElement('checkbox', 'salva_cookies')
	  		  -> setValue('10');
		
		$form -> createRadio('salvar', 'sim')
			  -> setChecked();

		$form -> createRadio('salvar', 'nao');
		
		if($this->request->isPost())
			$form->getValues($this->request->getPost());
		
		return $form;
	}
	
}
