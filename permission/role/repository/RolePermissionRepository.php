<?php
namespace permission\role\repository;

use tools\infrastructure\Repository;
use tools\infrastructure\Collector;
use permission\role\factory\RoleFactory;
use permission\role\objects\RolePermission;

class RolePermissionRepository extends Repository{
    protected RoleFactory $factory;

    public function __construct(){
        parent::__construct();
        $this->factory = new RoleFactory();
    }
    
    public function create(RolePermission $permission):void{
        $this->insert('rolePermission')        
            ->column('userId', $this->uuid($permission->userId()))
            ->column('r', $permission->read())
            ->column('w', $permission->write())
            ->column('e', $permission->edit())
            ->column('d', $permission->delete());
        $this->execute();
    }
    
    public function edit(RolePermission $permission):void{
        $this->update('rolePermission')
            ->column('r', $permission->read())
            ->column('w', $permission->write())
            ->column('e', $permission->edit())
            ->column('d', $permission->delete())
            ->where()->eq('userId', $this->uuid($permission->userId()));
        $this->execute();
    }
    
    public function listRolePermission(array $where):Collector{
        $this->select('rolePermission')
            ->join()->inner('role', 'userId', 'rolePermission', 'userId');

        if(isset($where['userId'])){
            $this->where()->eq('userId', $this->uuid($where['userId']));
        }
        $this->pagination()->set($where);
        $this->execute();
        return $this->factory->map(
            $this->results()
        );
    }
}