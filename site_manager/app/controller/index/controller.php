<?php
require_once APP.DS.'model'.DS.'Pagina.php';
class IndexController extends AppController {
	
	public function index($param = null) {
		$this->cancelView();
		
		$pagina = new PaginaModel();
		
		printrx($pagina->getAllFetched());
		
	}
}
