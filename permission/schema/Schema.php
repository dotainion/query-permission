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
}
