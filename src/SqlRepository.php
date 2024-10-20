<?php
namespace src;

use src\database\Table;
use src\infrastructure\SqlId;
use src\security\Connection;

class SqlRepository extends Table{
	protected Connection $db;

	public function __construct(){
		parent::__construct();
		$this->db = Connection::instance();
	}

	public function query($statement):self{
		$this->db->query($statement);
		$this->db->commit();
		return $this;
	}

	public function execute():self
	{
		$this->query($this->toString());
		$this->reset();
		return $this;
	}

	public function closeConnection(){
		$this->db->close();
	}

	public function statement(){
		$this->db->statement();
	}

	public function results(){
		return $this->db->results() ?? [];
	}

	public function uuid($uuid){
		if($uuid === null){
			return null;
		}
		if(is_array($uuid)){
			$ids =[];
			foreach($uuid as $id){
				$ids[] = (new SqlId())->toBytes((string)$id);
			}
			return $ids;
		}
		return (new SqlId())->toBytes((string)$uuid);
	}

	public function setUserId(string $userId):void{
		$this->db->setUserId(new SqlId($userId));
	}
}

?>