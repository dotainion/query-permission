<?php
namespace permission\module\permission\logic;

use InvalidArgumentException;
use permission\infrastructure\SqlId;
use permission\module\permission\action\PermissionRepository;
use permission\module\permission\factory\PermissionFactory;

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

        if(!$permission->read() && !$permission->write() && !$permission->edit() && !$permission->delete()){
            throw new InvalidArgumentException('At least one permission (read, write, edit, or delete) must be granted to save changes.');
        }

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
