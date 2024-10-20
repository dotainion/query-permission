<?php
namespace permission\module\permission\logic;

use permission\infrastructure\Collector;
use permission\infrastructure\SqlId;
use permission\module\permission\action\PermissionRepository;

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
