<?php
namespace src\module\permission\factory;

use src\infrastructure\Collector;
use src\infrastructure\Factory;
use src\module\permission\objects\Permission;

class PermissionFactory extends Collector{
    use Factory;

    public function __construct(){
    }

    public function mapResult($record):Permission{
        return new Permission(
            $this->uuid($record['id']),
            (string)$record['table'],
            (bool)$record['r'],
            (bool)$record['w'],
            (bool)$record['e'],
            (bool)$record['d']
        );
    }
}