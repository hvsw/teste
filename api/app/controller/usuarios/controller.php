<?php
class UsuariosController extends AppController
{
	
	protected $useSession = true;
	
	public function index () {
		//$this->session->setParam('nome', 'william');
		//$this->session->remove('nome');	
		printrx($_SESSION);
	}

	public function login() {
		
	}

	private function logar() {
		
	}
	
}
