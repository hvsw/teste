<?php

class EventoModel extends Model{
	
	protected $pk 			= 'id_evento';
	protected $tableName 	= 'evento';
	
	public function getEventoByPacote($id_pacote = NULL, $campos = NULL) {
		$result = $this->query("SELECT " . $this->formataCampos($campos) . " FROM ".$this->tableName." WHERE id_pacote='". $id_pacote ."' LIMIT 1");
		if(mysql_num_rows($result) <= 0)
			return NULL;
		$retorno = array();
		while($l = mysql_fetch_assoc($result))
			$retorno = $l;
		
		$this->id = $retorno[$this->pk];
		
		return $retorno;
	}
	
	public function idEventoByPacote($id_pacote) {
		$result = $this->query("SELECT " . $this->pk . " FROM " . $this->tableName . " WHERE id_pacote='".$id_pacote."'");
		
		if($this->lines <= 0)
			return NULL;
		
		$retorno = array();
		while($l = mysql_fetch_assoc($result))
			$retorno = $l;
		
		$this->id = $retorno[$this->pk];
		
		return $this->id;
	}
	
	
	public function atualizaEvento($campos = array(), $id_pacote) {
		if(empty($campos))
			return NULL;
		
		$atualizou = $this->atualiza($campos,"id_pacote='".$id_pacote."'");
		
	}
	
	//public function addEvento($campos_valores) {
		//$sql = "INSERT INTO " . $this->tableName . "";
	//}
	
	
	/*public function saveEvento($token,$status = 1, $urban = 0, $data_cadastro = NULL) {
		$data_cadastro = is_null($data_cadastro) ? date("Y-m-d h:s") : $data_cadastro; 

		$sql = "INSERT INTO device (`token`,`data_cadastro`, `status`, `urbanairship`) VALUES (";
		$sql .= "'" . $token ."','". $data_cadastro . "','" . $status . "','" . $urban . "'";
		$sql .= ")";

		$inseriu = $this->query($sql);
		if(!$inseriu) {
			return NULL;			
		}
		$this->id = mysql_insert_id();
		
		return TRUE;
	}*/
	
}
