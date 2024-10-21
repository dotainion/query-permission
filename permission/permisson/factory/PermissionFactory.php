<?php
namespace permission\module\permission\factory;

use permission\infrastructure\Collector;
use permission\infrastructure\Factory;
use permission\module\permission\objects\Permission;

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