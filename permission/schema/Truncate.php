<?php
namespace permission\schema;

use Exception;
use permission\schema\Table;

class Truncate{
    protected $sql = null;

    public function __construct()
    {
        $this->sql = new Table();
    }

    public function user(){
        $this->sql->truncate('user');
        $this->sql->execute()->reset();
    }

    public function credential(){
        $this->sql->truncate('credential');
        $this->sql->execute()->reset();
    }

    public function products(){
        $this->sql->truncate('products');
        $this->sql->execute()->reset();
    }

    public function run(){
        foreach(get_class_methods($this) as $method){
            if($method === '__construct' || $method === 'run') continue;
            if (!is_callable([$this, $method])) {
                throw new Exception($method.' is not callable');
            }
            $this->$method();
        }
    }
}
