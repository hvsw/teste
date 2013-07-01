<?php
class PacoteModel extends Model{
	protected $pk = 'id_pacote';
	protected $tableName = 'pacote';
	
	public function getPacote($codigoRastreio,$deviceId, $ciaId, $campos = NULL) {
		
		$sql 		= "SELECT ".$this->formataCampos($campos)." FROM ".$this->tableName." WHERE codigo='" . $codigoRastreio . "' AND id_device='" . $deviceId . "'";
		$result	 	= $this->query($sql);
		
		if(mysql_num_rows($result) <= 0)
			return NULL;
		$retorno = array();
		while($l = mysql_fetch_assoc($result))
			$retorno = $l;
		
		$this->id = $retorno[$this->pk];
		
		return $retorno;
	}
	
	public function getPacotesAtivosResult() {
		$campos = array('id_device','id_pacote','codigo','data_cadastro','ultima_atualizacao','status_atual');

		$sql 	= 	"SELECT " . $this->formataCampos($campos) . " FROM " . $this->tableName;
		$sql	=	$sql . " LEFT JOIN device ON device.id_device =".$this->tableName.".id_device";
		$sql	=	$sql . " WHERE ".$this->tableName.".recebe_notificacao=1 AND ".$this->tableName.".finalizado!=1 AND device.status=1";

		return $this->query($sql); 
	}
	
	public function getPacotesResult($filtros = NULL, $campos = "*", $grupo = NULL) {
		$sql 	= "SELECT " . $this->formataCampos($campos) . " FROM " . $this->tableName;
		// Add filtros
		if(!is_null($filtros))
			$sql = $sql." WHERE ".$filtros;
		
		// Add grupo
		if(!is_null($grupo))
			$sql = $sql." GROUP BY ".$grupo;
		
		$result	= $this->query($sql);
		
		return $result;
	}
	
	public function getPacotes($filtros = NULL, $campos = "*") {
		$sql 	= "SELECT " . $this->formataCampos($campos) . " FROM " . $this->tableName;
		// Add filtros
		if(!is_null($filtros))
			$sql = $sql." WHERE ".$filtros;
		
		$result	= $this->query($sql);
		
		// Se vazio retorna null
		if(mysql_num_rows($result) <= 0)
			return NULL;
		
		// Monta array
		$retorno = array();
		while($l = mysql_fetch_assoc($result))
			$retorno = $l;
		
		// retorna
		return $retorno;
	}
	
	public function atualizaPacote($campos_valores, $token = NULL) {
		if(is_null($token)) 		
			return $this->atualiza($campos_valores, $this->pk."=".$this->id);
		else {
			return $this->atualiza($campos_valores, "token='".$token."'");
		}
	}
	
	public function deletePacote($id_pacote) {
		$sql = "DELETE FROM " . $this->tableName . " WHERE " . $this->pk . "='" . $id_pacote . "'";
		$this->query($sql);
		return mysql_affected_rows($this->_conexao) > 0;
	}
	
	public function ativaPacote($id_pacote) {
		$atualizacao = array('recebe_notificacao' => 1);
		return $this->atualiza($atualizacao,$this->pk . "=" . $id_pacote);
	}
	
	public function desativaPacote($id_pacote) {
		$atualizacao = array('recebe_notificacao' => 0);
		return $this->atualiza($atualizacao,$this->pk . "=" . $id_pacote);
	}
	
	public function getPacotesPush() {
		
		$sql 	= 	"SELECT device.token, ".$this->tableName.".id_pacote, ".$this->tableName.".codigo, ".$this->tableName.".status_atual ";
		$sql	=	$sql . " FROM " . $this->tableName;
		$sql	=	$sql . " LEFT JOIN device ON device.id_device =".$this->tableName.".id_device";
		$sql	=	$sql . " WHERE ".$this->tableName.".recebe_notificacao=1 AND device.status=1";
		$sql	=	$sql . " AND ".$this->tableName.".atualizado=1";
		
		return $this->query($sql);
	}
	
	public function savePacote($deviceId,$codigoRastreio,$ciaId, $recebeNotificacao = 1) {
		$sql = "INSERT INTO " . $this->tableName . " (`id_device`,`id_companhia`, `codigo`, `recebe_notificacao`, `data_cadastro`) VALUES (";
		$sql .= "'" . $deviceId ."','". $ciaId . "','". $codigoRastreio . "',". $recebeNotificacao . ",'".date("Y-m-h h:s:i")."'";
		$sql .= ")";
		
		$inseriu = $this->query($sql);
		if(!$inseriu)
			return FALSE;
		
		$this->id = mysql_insert_id();
		return TRUE;
	}
	
	public function atualizaQtdEventos($id_pacote = NULL) {
		//update eventos set qtd_eventos
	}
	
}
