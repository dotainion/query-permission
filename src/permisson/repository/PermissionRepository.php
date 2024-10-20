<?php
namespace src\module\permission\action;

use src\infrastructure\Collector;
use src\module\permission\factory\PermissionFactory;
use src\module\permission\objects\Permission;
use src\SqlRepository;

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
            ->column('table', $permission->table())
            ->column('r', $permission->read())
            ->column('w', $permission->write())
            ->column('e', $permission->edit())
            ->column('d', $permission->delete())
            ->where()->eq('id', $this->uuid($permission->id()));
        $this->execute();
    }
    
    public function listPermission(array $where=[]):Collector{
        $this->select('permission');

        if(isset($where['id'])){
            $this->where()->eq('id', $this->uuid($where['id']));
        }
        if(isset($where['table'])){
            $this->where()->eq('table', $this->uuid($where['table']));
        }
        $this->execute();
        return $this->factory->map(
            $this->results()
        );
    }
}