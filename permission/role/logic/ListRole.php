<?php
namespace permission\role\logic;

use tools\infrastructure\Collector;
use tools\infrastructure\Id;
use permission\role\repository\RoleRepository;

class ListRole{
    protected RoleRepository $repo;

    public function __construct(){
        $this->repo = new RoleRepository();
    }

    public function byUserId(Id $userId):Collector{
        return $this->repo->listRoles([
            'userId' => $userId
        ]);
    }
}