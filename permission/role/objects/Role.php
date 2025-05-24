<?php
namespace permission\role\objects;

use tools\infrastructure\Id;
use tools\infrastructure\IId;
use tools\infrastructure\IObjects;
use permission\infrastructure\RoleAttributes;

class Role implements IObjects{
    protected Id $userId;
    protected string $label;
    protected RolePermission $permission;

    public function __construct(){
        $this->userId = new Id();
    }
        
    public function id():IId{
        return $this->userId;
    }

    public function userId():IId{
        return $this->userId;
    }

    public function label():string{
        return $this->label;
    }

    public function permission():RolePermission{
        return $this->permission;
    }

    public function isAdmin():bool{
        return $this->label() === (string)RoleAttributes::ADMIN;
    }

    public function isModerator():bool{
        return $this->label() === (string)RoleAttributes::MODERATOR;
    }

    public function isMember():bool{
        return $this->label() === (string)RoleAttributes::MEMBER;
    }

    public function isGuest():bool{
        return $this->label() === (string)RoleAttributes::GUEST;
    }

    public function setUserId(string $userId):void{
        $this->userId->set($userId);
    }

    public function setLabel(string $label):void{
        $this->label = $label;
    }

    public function setPermission(RolePermission $permission):void{
        $this->permission = $permission;
    }
}

