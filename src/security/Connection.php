<?php
namespace src\security;

use Exception;
use InvalidArgumentException;
use mysqli_result;
use mysqli_sql_exception;
use src\infrastructure\SqlId;

class Connection extends DbCredentials{
	protected $connection;
	protected $statement;
	protected $reference;
	protected $results;
	protected SqlId $userId;
	protected static $_instance;

	public function __construct(){
		$this->connect();
	}

	private function assertConnected():bool{
		if (mysqli_connect_errno()) {
			throw new Exception('Failed to connect to MySQL: ' . mysqli_connect_error());
		}
		if(! $this->connection){
			throw new Exception('Database not connected.');
		}
		return true;
	}

	public static function instance()
	{
	  if(self::$_instance === null){
		self::$_instance = new self;
	  }
	  return self::$_instance;
	}

	public function connect(){
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		$this->connection = mysqli_connect(
            $this->server(), 
            $this->username(), 
            $this->password(), 
            $this->database()
        );
		$this->assertConnected();
		return $this;
	}

	public function query($statement):self{
		try {
			$this->statement = $statement;
			$this->reference = mysqli_query($this->connection(), $this->statement, MYSQLI_USE_RESULT);
		} catch (mysqli_sql_exception $exception) {
			throw new InvalidArgumentException($exception->getMessage());
		}
		return $this;
	}

	public function commit():void{
		if(!$this->reference instanceof mysqli_result) return;
		$this->results = mysqli_fetch_all($this->reference, MYSQLI_ASSOC);
	}

	public function close():void{
		mysqli_close($this->connection());
	}

	public function connection(){
		return $this->connection;
	}

	public function statement():string{
		return $this->statement;
	}

	public function results():?array{
		return $this->results;
	}

	public function setUserId($userId):void{
		$this->userId = $userId;
	}

	public static function userId():SqlId{
		return self::$userId;
	}
}

?>