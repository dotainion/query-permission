<?php
namespace permission\role\factory;

use tools\infrastructure\Collector;
use tools\infrastructure\Factory;
use permission\role\objects\Role;

class RoleFactory extends Collector{
    use Factory;

    protected RolePermissionFactory $factory;

    public function __construct(){
        $this->factory = new RolePermissionFactory();
    }
    
    public function mapResult($record):Role{
        $role = new Role();
        $role->setUserId($this->uuid($record['userId']));
        $role->setLabel($record['label']);
        $role->setPermission($this->factory->mapResult($record));
        return $role;
    }
}