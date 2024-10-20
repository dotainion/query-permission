<?php
namespace permission\infrastructure;

class Collector{
    protected $collected = [];

    public function add($item):void{
        $this->collected[] = $item;
    }

    public function list():array{
        return $this->collected;
    }
    
    public function first(){
        return $this->collected[0] ?? null;
    }

    public function clear():self{
        $this->collected = [];
        return $this;
    }

    public function hasItem():bool{
        return !empty($this->collected);
    }

    public function isEmpty():bool{
        return !$this->hasItem();
    }

    public function assertHasItem(string $message='You have not permission.'):bool{
        if(!$this->hasItem()){
            throw new PermissionException($message);
        }
        return true;
    }
}