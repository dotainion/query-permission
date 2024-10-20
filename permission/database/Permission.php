<?php
namespace permission\database;

use Exception;
use permission\infrastructure\PermissionException;
use permission\module\permission\factory\PermissionFactory;
use permission\module\permission\objects\IPermission;
use permission\security\Connection;

class Permission {
    protected Table $table;
    protected Connection $db;
    protected PermissionFactory $factory;
    protected static ?string $userId = null;
    protected static bool $requirePermission = true;
    protected string $tableName = 'permission';

    public function __construct(Table $table){
        $this->table = $table;
		$this->db = Connection::instance();
        $this->factory = new PermissionFactory();
    }

    public function permission():self{
        if(!self::requirePermission()){
            return $this;
        }
        $where = new Where($this->table);
        $where->eq('id', self::userId(), $this->tableName);
        $where->eq('table', $this->table->tableName(), $this->tableName);

		$this->db->query("SELECT * FROM `permission` " . $where->get());
		$this->db->commit();

        $collector = $this->factory->map($this->db->results());
        $collector->assertHasItem('You do not have permission.');
        return $this->assert($collector->first());
    }

    public function assert(IPermission $permission):self{
        if($this->isRead() && !$permission->read()){
            throw new PermissionException('You do not have permission to view or edit this resource.');
        }
		if($this->isWrite() && !$permission->write()){
            throw new PermissionException('You do not have permission to add or create new resources.');
        }
		if($this->isEdit() && !$permission->edit()){
            throw new PermissionException('You do not have permission to modify this resource.');
        }
		if($this->isDelete() && !$permission->delete()){
            throw new PermissionException('Error: You do not have permission to delete this resource.');
        }
        if(!$this->isRead() && !$this->isWrite() && !$this->isEdit() && !$this->isDelete()){
            throw new Exception('You need to first call select, insert, update or delete before using calling permission.');
        }
        return $this;
    }

    public static function userId():?string{
        return self::$userId;
    }

    public static function setUserId(string $userId):void{
        self::$userId = $userId;
    }

    public static function requirePermission():bool{
        return self::$requirePermission;
    }

    public static function setRequirePermission(bool $requirePermission):void{
        self::$requirePermission = $requirePermission;
    }

    public function isRead():bool{
        return str_contains($this->table->getQuery(), 'SELECT');
    }

    public function isWrite():bool{
        return str_contains($this->table->getQuery(), 'INSERT');
    }

    public function isEdit():bool{
        return str_contains($this->table->getQuery(), 'UPDATE');
    }

    public function isDelete():bool{
        return str_contains($this->table->getQuery(), 'DELETE');
    }
}