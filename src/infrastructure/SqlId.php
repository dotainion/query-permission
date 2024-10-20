<?php

namespace src\infrastructure;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

class SqlId{
    protected $uuid;

    public function __construct(?string $uuid = null){
        ($uuid !== null) && $this->set($uuid);
    }

    final public function __toString():string{
        return $this->toString();
    }

    final public function id():self{
        return $this;
    }

    final public function fromBytes(string $uuid):self{
        $this->uuid = (string)Uuid::fromBytes($uuid);
        return $this;
    }

    final public function hasId():bool{
        return $this->uuid !== null;
    }

    final public function new():self{
        $this->uuid = Uuid::uuid4();
        return $this;
    }

    final public function set(?string $uuid):self{
        $this->assert((string)$uuid);
        $this->uuid = (string)Uuid::fromString((string)$uuid);
        return $this;
    }

    public function isValid(?string $uuid):bool{
        return Uuid::isValid($this->_replace((string)$uuid));
    }

    public function assert(?string $uuid, string $message='Invalid uuid.'):bool{
        if(!$this->isValid($uuid)){
            throw new InvalidArgumentException($message);
        }
        return true;
    }

    public function toString():string{
        return $this->_replace((string)$this->uuid);
    }

    public function toBytes(string $uuid):string{
        $this->assert((string)$uuid);
      return $this->_replace(Uuid::fromString((string)$uuid)->getBytes());
    }

    private function _replace(string $uuid){
        return str_replace('"', '~~~~~', $uuid);
    }
}
