<?php
namespace src\infrastructure;

use Exception;

trait Factory{
    public function map(array $records){
        $this->clear();
        if (!method_exists($this, 'mapResult')){
            throw new Exception('Factory must have method mapResult.');
        }
        foreach($records as $record){
            if (!$this->isValid($record)){
                throw new Exception('Factory record must be assosiative array.');
            }
            $this->add($this->mapResult($record));
        };
        return $this;
    }

    public function isValid($record){
        if (!is_array($record)){
            return false;
        }
        return true;
    }

    public function isValidUUid($uuid):bool{
        return (new SqlId())->isValid((string)$uuid);
    }

    public function uuid($uuidBytes){
        if($this->isValidUUid($uuidBytes)){
            return $uuidBytes;
        }
        return (new SqlId())->fromBytes((string)$uuidBytes)->toString();
    }
}