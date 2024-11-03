<?php
namespace permission\permission\logic;

use InvalidArgumentException;
use permission\permission\action\PermissionRepository;
use permission\permission\factory\PermissionFactory;

class SavePermission{
    protected PermissionRepository $repo;
    protected PermissionFactory $factory;

    public function __construct(){
        $this->repo = new PermissionRepository();
        $this->factory = new PermissionFactory();
    }

    public function set($userId, $table, $read, $write, $edit, $delete):void{
        $permission = $this->factory->mapResult([
            'id' => $userId,
            'table' => $table,
            'r' => $read,
            'w' => $write,
            'e' => $edit,
            'd' => $delete
        ]);

        $collector = $this->repo->listPermission([
            'id' => $permission->id(),
            'table' => $permission->table()
        ]);
        if($collector->hasItem()){
            $this->repo->edit($permission);
            return;
        }
        $this->repo->create($permission);
    }
}
