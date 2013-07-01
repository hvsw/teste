<?php
class DeviceModel extends Model{

	protected $pk = 'id_device';
	protected $tableName = 'device';
	
	public function getDeviceByToken($token = NULL, $campos = NULL) {
		if(is_null($campos))
			$campos = $this->pk;
		
		$result = $this->query("SELECT " . $this->formataCampos($campos) . " FROM ".$this->tableName." WHERE token='". $token ."' LIMIT 1");
		
		if(mysql_num_rows($result) <= 0)
			return NULL;
		
		$retorno = array();
		
		while($l = mysql_fetch_assoc($result))
			$retorno = $l;
		
		$this->id = $retorno[$this->pk];
		
		return $retorno;
	}
	
	public function getDevicesNaoRegistradosUAS() {
		$sql = "SELECT * FROM ".$this->tableName." WHERE urbanairship=0";
		return $this->query($sql);
	}
	
	public function atualizaAcesso() {
		if(is_null($this->id))
			return null;
		$sql = "UPDATE " . $this->tableName . " SET ultimo_acesso=NOW() WHERE " . $this->pk . "='" . $this->id . "'";
		$this->query($sql);
	}
	
	public function registraDeviceUrban($token) {
		return $this->atualiza(array('urbanairship' => 1), "token='".$token."'");
	}
	
	public function desativaNotificacoes() {
		if(is_null($this->id))
			return NULL;

		return $this->atualiza(array('status' => 0),$this->pk."=".$this->id);
	}
	
	public function ativaNotificacoes() {
		if(is_null($this->id))
			return NULL;
		
		return $this->atualiza(array('status' => 1),$this->pk."=".$this->id);
	}
	
	public function saveDevice($token,$status = 1, $urban = 0, $data_cadastro = NULL) {
		$data_cadastro = is_null($data_cadastro) ? date("Y-m-d h:s") : $data_cadastro; 

		$sql = "INSERT INTO device (`token`,`data_cadastro`, `status`, `urbanairship`, `ultimo_acesso`) VALUES (";
		$sql .= "'" . $token ."','". $data_cadastro . "','" . $status . "','" . $urban . "','" . $data_cadastro . "'";
		$sql .= ")";

		$inseriu = $this->query($sql);
		if(!$inseriu) {
			return NULL;			
		}
		$this->id = mysql_insert_id();
		
		return TRUE;
	}
	
}
