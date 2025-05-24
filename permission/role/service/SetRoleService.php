<?php
namespace permission\role\service;

use tools\infrastructure\Assert;
use permission\infrastructure\RoleAttributes;
use tools\infrastructure\Service;
use permission\role\factory\RoleFactory;
use permission\role\logic\SetRole;

class SetRoleService extends Service{
    protected SetRole $role;
    protected RoleFactory $factory;

    public function __construct(){
        parent::__construct(false);
        $this->role = new SetRole();
        $this->factory = new RoleFactory();
    }
    
    public function process(string $userId, string $label, bool $read, bool $write, bool $edit, bool $delete){
        Assert::validUuid($userId, 'User not found.');
        Assert::inArray($label, RoleAttributes::all(), 'Invalid role type.');

        $role = $this->factory->mapResult([
            'userId' => $userId,
            'label' => $label,
            'r' => $read,
            'w' => $write,
            'e' => $edit,
            'd' => $delete,
        ]);

        $this->role->set($role);
        
        $this->setOutput($role);
        return $this;
    }
}