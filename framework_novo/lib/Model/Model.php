<?php
abstract class Model {
	public 		$id 	= NULL;
	public 		$lines 	= NULL;
	
	protected 	$pk 	= NULL;
	protected	$debug 	= FALSE;
	protected	$result = NULL;
	protected 	$tableName;
	protected	$_db;
	protected	$_conexao;
	
	public function debug($status = TRUE) {
		$this->debug = $status;
	}
	
	public function __construct($confName = 'database') {
		
		$dbconf = Conf::r($confName);
		$dbconf =& $dbconf[ENV];

		if(empty($dbconf) || is_null($dbconf))
			throw new Exception("Sem configuracoes de banco", 1);

		$this->connect($dbconf);
		
		$this->selectDb($dbconf['db']);
		
	}
	
	public function connect($dbconf) {
		$this->_conexao = mysql_connect($dbconf['server'],$dbconf['user'],$dbconf['pass']);
		if(!$this->_conexao) 		
			throw new Exception(mysql_error(), mysql_errno());
	}
	
	public function selectDb($dbName) {
		$this->_db = mysql_select_db($dbName,$this->_conexao);
		if(!$this->_db)
			throw new Exception(mysql_error(), mysql_errno());
	}
	
	public function reset() {
		$this->id 		= NULL;
		$this->lines 	= 0;
	}
	
	public function query($sql) {
		$this->result 	= mysql_query($sql,$this->_conexao);

		if(is_resource($this->result)){
			if(strtolower(substr($sql, 0,6)) == "select")
				$this->lines = mysql_num_rows($this->result);
			else
				$this->lines = mysql_affected_rows($this->result);
		}

		if($this->debug) {
			printr("Resource: ".$this->result);
			printr("SQL: ".$sql);
		}
		
		return $this->result;
	}
	
	protected function formataCamposUpdate($campos = array()) {
		
		if(empty($campos))
			return null;

		foreach($campos as $nome => $valor) {
			if(substr($valor, 0,1) == "[" && substr($valor, 0,-1) == "]")
				$strCampo = $nome . "=" . $valor ;
			else
				$strCampo = $nome . "='" . $valor . "'";

			$campos_update[] = $strCampo;
		}		
		
		$retorno = implode(",", $campos_update);
		
		return $retorno;
		
	}
	
	protected function formataCamposInsert($campos_valores) {
		$campos	 = array_keys($campos_valores);
		$valores = array();
		
		foreach($campos_valores as $campo => $valor) {
			if(substr($valor, 0,1) == "[" && substr($valor, 0,-1) == "]")
				$valores[] = $valor ;
			else
				$valores[] = "'" . $valor . "'";
		}
		
		return "(" . implode(",", array_keys($campos_valores)) . ") VALUES (" . implode(",", $valores) . ")";
	}
	
	public function atualiza($campos,$filtros = NULL) {

		if(is_array($campos))
			$campos = $this->formataCamposUpdate($campos);
		
		
		$sql = "UPDATE ".$this->tableName . " SET " . $campos;

		if(!is_null($filtros))
			$sql = $sql." WHERE ".$filtros;
		
		return $this->query($sql);
	}
	
	public function save($campos_valores) {
		
		if(is_array($campos_valores))
			$campos_valores = $this->formataCamposInsert($campos_valores);
		
		$sql	= "INSERT INTO " . $this->tableName . " " . $campos_valores;
		$result = $this->query($sql);
		
		if($result)
			$this->id = mysql_insert_id($this->_conexao);

		return $result;
	}
	
	public function getAll() {
		$sql = "SELECT * FROM " . $this->tableName;
		return $this->query($sql);
	}
	
	public function getAllFetched() {
		$result = $this->getAll();
		$registros = array();
		while($l = mysql_fetch_assoc($result))
			$registros[] = $l;
		
		return $registros;
	}
	
	public function find() {
		
	}
	
}





