<?php
namespace permission\role\objects;

use tools\infrastructure\Id;
use tools\infrastructure\IId;
use tools\infrastructure\IObjects;

class RolePermission implements IObjects{
    protected Id $userId;
    protected bool $read;
    protected bool $write;
    protected bool $edit;
    protected bool $delete;

    public function __construct(){
        $this->userId = new Id();
    }
        
    public function id():IId{
        return $this->userId;
    }

    public function userId():IId{
        return $this->userId;
    }

    public function read():bool{
        return $this->read;
    }

    public function write():bool{
        return $this->write;
    }

    public function edit():bool{
        return $this->edit;
    }

    public function delete():bool{
        return $this->delete;
    }

    public function setUserId(string $userId):void{
        $this->userId->set($userId);
    }

    public function setRead(bool $read):void{
        $this->read = $read;
    }

    public function setWrite(bool $write):void{
        $this->write = $write;
    }

    public function setEdit(bool $edit):void{
        $this->edit = $edit;
    }

    public function setDelete(bool $delete):void{
        $this->delete = $delete;
    }
}

