<?php
abstract class Model {
	public 		$id 	= NULL;
	public 		$lines 	= NULL;
	
	protected 	$pk 	= NULL;
	protected	$debug 	= FALSE;
	protected	$result = NULL;
	protected 	$tableName;
	
	public function debug($status = TRUE) {
		$this->debug = $status;
	}
	
	public function __construct($confName = NULL) {
		$indice = is_null($confName) ? 'database' : $confName;
		$dbconf = Conf::r($indice);
		$dbconf =& $dbconf[ENV];
		$this->_conexao = mysql_connect($dbconf['server'],$dbconf['user'],$dbconf['pass']) or die('Nao foi possivel conectar');
		mysql_select_db($dbconf['db'],$this->_conexao);
		
	}
	
	public function query($sql) {
		$this->result 	= mysql_query($sql,$this->_conexao);

		if(is_resource($this->result)){
			if(strtolower(substr($sql, 0,6)) == "select")
				$this->lines = mysql_num_rows($this->result);
			else
				$this->lines 	= mysql_affected_rows($this->result);
		}

		if($this->debug) {
			printr("Resource: ".$this->result);
			printr("SQL: ".$sql);
		}
		
		return $this->result;
	}
	
	public function formataCampos($campos = NULL) {
		if(is_null($campos))
			return $this->pk;
		else if(!is_array($campos) && is_string($campos))
			return $campos;
		else if(is_array($campos) && !empty($campos)) {
			foreach ($campos as &$campo)
				$campo = $this->tableName . "." . $campo;
			
			return implode(",",$campos);
		}
		
		return FALSE;
	}
	
	public function reset() {
		$this->id = NULL;
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
		
		$sql = "INSERT INTO " . $this->tableName . " " . $this->formataCamposInsert($campos_valores);
		$result = $this->query($sql);
		if($result)
			$this->id = mysql_insert_id();
		
		return $result;
	}
	
	protected function formataCamposInsert($campos_valores) {
		$campos	 = array_keys($campos_valores);
		$valores = array();
		
		foreach($campos_valores as $campo => $valor) {
			$valores[] = "'" . $valor . "'";
		}
		
		return "(" . implode(",", array_keys($campos_valores)) . ") VALUES (" . implode(",", $valores) . ")";
	}
	
	
	protected function formataCamposUpdate($campos = array()) {
		
		if(empty($campos))
			return FALSE;

		// SET asd=asd,asd=asd,...
		foreach($campos as $nome => $valor) {
			$strCampo = $nome . "='" . $valor . "'";
			$campos_update[] = $strCampo;
		}		
		
		$retorno = implode(",", $campos_update);
		
		return $retorno;
		
	}
	
}





