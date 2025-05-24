<?php
namespace permission\role\service;

use tools\infrastructure\Assert;
use tools\infrastructure\Id;
use tools\infrastructure\Service;
use permission\role\logic\ListRole;

class ListRoleService extends Service{
    protected ListRole $roles;

    public function __construct(){
        parent::__construct(false);
        $this->roles = new ListRole();
    }
    
    public function process(string $userId){
        Assert::validUuid($userId, 'User not found.');

        $collector = $this->roles->byUserId(new Id($userId));
        $this->setOutput($collector);
        return $this;
    }
}