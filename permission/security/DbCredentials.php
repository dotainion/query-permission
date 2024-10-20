<?php
namespace permission\security;

use Exception;

class DbCredentials{

    public function username():string{
		if(empty(getenv('DB_USERNAME'))){
			throw new Exception('Database DB_USERNAME is not set.');
		}
		return getenv('DB_USERNAME');
	}

	public function password():string{
		if(empty(getenv('DB_PASSWORD'))){
			throw new Exception('Database DB_PASSWORD is not set.');
		}
		return getenv('DB_PASSWORD');
	}

	public function server():string{
		if(empty(getenv('DB_SERVER'))){
			throw new Exception('Database DB_SERVER is not set.');
		}
		return getenv('DB_SERVER');
	}

	public function database():string{
		if(empty(getenv('DB_DATABASE'))){
			throw new Exception('Database DB_DATABASE is not set.');
		}
		return getenv('DB_DATABASE');
	}
}

?>