<?php
namespace permission\role\repository;

use tools\infrastructure\Repository;
use tools\infrastructure\Collector;
use permission\role\factory\RoleFactory;
use permission\role\objects\Role;

class RoleRepository extends Repository{
    protected RoleFactory $factory;

    public function __construct(){
        parent::__construct();
        $this->factory = new RoleFactory();
    }
    
    public function create(Role $role):void{
        $this->insert('role')        
            ->column('userId', $this->uuid($role->userId()))
            ->column('label', $role->label());
        $this->execute();
    }
    
    public function edit(Role $role):void{
        $this->update('role')        
            ->column('label', $role->label())
            ->where()->eq('userId', $this->uuid($role->userId()));
        $this->execute();
    }
    
    public function listRoles(array $where):Collector{
        $this->select('role')
            ->join()->inner('rolePermission', 'userId', 'role', 'userId');

        if(isset($where['userId'])){
            $this->where()->eq('userId', $this->uuid($where['userId']));
        }
        if(isset($where['label'])){
            $this->where()->eq('label', $where['label']);
        }
        $this->pagination()->set($where);
        $this->execute();
        return $this->factory->map(
            $this->results()
        );
    }
}