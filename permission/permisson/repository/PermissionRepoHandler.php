<?php
namespace permission\permission\action;

use permission\SqlRepository;

class PermissionRepoHandler extends SqlRepository{

    public function __construct(){
        parent::__construct($this);
    }

    public function onQueryStart(string $dml):void{
        
    }
}