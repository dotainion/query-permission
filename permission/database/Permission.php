<?php
namespace permission\database;

use Exception;
use permission\infrastructure\PermissionException;
use permission\module\permission\logic\ListPermission;
use permission\module\permission\objects\IPermission;
use permission\security\Connection;

class Permission {
    protected Table $table;
    protected ListPermission $permissions;

    public function __construct(Table $table){
        $this->table = $table;
        $this->permissions = new ListPermission();
    }

    public function permission():self{
        $collector = $this->permissions->byTable(Connection::userId(), $this->table->tableName());
        $collector->assertHasItem('You do not have permission.');
        return $this->assert($collector->first());
    }

    public function assert(IPermission $permission):self{
        if($this->isRead() && !$permission->read()){
            throw new PermissionException('You do not have permission to view or edit this resource.');
        }
		if($this->isWrite() && !$permission->write()){
            throw new PermissionException('You do not have permission to add or create new resources.');
        }
		if($this->isEdit() && !$permission->edit()){
            throw new PermissionException('You do not have permission to modify this resource.');
        }
		if($this->isDelete() && !$permission->delete()){
            throw new PermissionException('Error: You do not have permission to delete this resource.');
        }
        if(!$this->isRead() && !$this->isWrite() && !$this->isEdit() && !$this->isDelete()){
            throw new Exception('You need to first call select, insert, update or delete before using calling permission.');
        }
        return $this;
    }

    public function isRead():bool{
        return str_contains($this->table->getQuery(), 'SELECT');
    }

    public function isWrite():bool{
        return str_contains($this->table->getQuery(), 'INSERT');
    }

    public function isEdit():bool{
        return str_contains($this->table->getQuery(), 'UPDATE');
    }

    public function isDelete():bool{
        return str_contains($this->table->getQuery(), 'DELETE');
    }
}