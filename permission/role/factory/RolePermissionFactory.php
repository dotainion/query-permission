<?php
namespace permission\role\factory;

use tools\infrastructure\Collector;
use tools\infrastructure\Factory;
use permission\role\objects\RolePermission;

class RolePermissionFactory extends Collector{
    use Factory;

    public function __construct(){
    }
    
    public function mapResult($record):RolePermission{
        $permission = new RolePermission();
        $permission->setUserId($this->uuid($record['userId']));
        $permission->setRead((bool)$record['r']);
        $permission->setWrite((bool)$record['w']);
        $permission->setEdit((bool)$record['e']);
        $permission->setDelete((bool)$record['d']);
        return $permission;
    }
}