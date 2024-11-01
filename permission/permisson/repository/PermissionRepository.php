<?php
namespace permission\permission\action;

use permission\infrastructure\Collector;
use permission\permission\factory\PermissionFactory;
use permission\permission\objects\Permission;
use permission\SqlRepository;

class PermissionRepository extends SqlRepository{
    protected PermissionFactory $factory;

    public function __construct(){
        parent::__construct();
        $this->factory = new PermissionFactory();
    }
    
    public function create(Permission $permission):void{
        $this->insert('permission')        
            ->column('id', $this->uuid($permission->id()))
            ->column('table', $permission->table())
            ->column('r', $permission->read())
            ->column('w', $permission->write())
            ->column('e', $permission->edit())
            ->column('d', $permission->delete());
        $this->execute();
    }
    
    public function edit(Permission $permission):void{
        $this->update('permission')
            ->column('r', $permission->read())
            ->column('w', $permission->write())
            ->column('e', $permission->edit())
            ->column('d', $permission->delete())
            ->where()
            ->eq('id', $this->uuid($permission->id()))
            ->eq('table', $permission->table());
        $this->execute();
    }
    
    public function listPermission(array $where=[]):Collector{
        $this->select('permission');

        if(isset($where['id'])){
            $this->where()->eq('id', $this->uuid($where['id']));
        }
        if(isset($where['table'])){
            $this->where()->eq('table', $where['table']);
        }
        $this->execute();
        return $this->factory->map(
            $this->results()
        );
    }
}