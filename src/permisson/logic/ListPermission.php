<?php
namespace src\module\permission\logic;

use src\infrastructure\Collector;
use src\infrastructure\SqlId;
use src\module\permission\action\PermissionRepository;

class ListPermission{
    protected PermissionRepository $repo;

    public function __construct(){
        $this->repo = new PermissionRepository();
    }

    public function byUserId(SqlId $userId):Collector{
        return $this->repo->listPermission([
            'id' => $userId
        ]);
    }

    public function byTable(SqlId $userId, string $table):Collector{
        return $this->repo->listPermission([
            'id' => $userId,
            'table' => $table
        ]);
    }
}
