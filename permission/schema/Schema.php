<?php
namespace permission\schema;

class Schema{
    protected $sql = null;

    public function __construct(){
        $this->sql = new Table();
    }

    public function permission(){
        $this->sql->create('permission')
            ->column('id')->bindary()
            ->column('table')->string()
            ->column('r')->bool()
            ->column('w')->bool()
            ->column('e')->bool()
            ->column('d')->bool();
        return $this->sql->execute();
    }

    public function role(){
        $this->sql->create('role')
            ->column('userId')->bindary()
            ->column('label')->string();
        return $this->sql->execute();
    }

    public function rolePermission(){
        $this->sql->create('rolePermission')
            ->column('userId')->bindary()
            ->column('r')->bool()
            ->column('w')->bool()
            ->column('e')->bool()
            ->column('d')->bool();
        return $this->sql->execute();
    }
}
