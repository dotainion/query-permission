<?php
namespace permission\database;

use Exception;
use permission\infrastructure\PermissionException;
use permission\infrastructure\SqlId;
use permission\permission\factory\PermissionFactory;
use permission\permission\objects\IPermission;
use permission\security\Connection;

class Permission {
    protected Table $table;
    protected Connection $db;
    protected PermissionFactory $factory;
    protected static ?string $userId = null;
    protected static bool $requirePermission = true;
    protected static string $tableName = 'permission';
    protected static array $allowTableBypass = [];

    public function __construct(Table $table){
        $this->table = $table;
		$this->db = Connection::instance();
        $this->factory = new PermissionFactory();
    }

    public function permission():self{
        if(!self::requirePermission() || in_array($this->table->tableName(), self::$allowTableBypass)){
            return $this;
        }
        (new SqlId())->assert(self::userId(), 'User to access permission not found.');
        $where = new Where($this->table);
        $where->eq('id', (new SqlId())->toBytes(self::userId()), self::tableName());
        $where->eq('table', $this->table->tableName(), self::tableName());
        $limit = new Pagination($this->table);
        $limit->limit(1);

		$this->db->query("SELECT * FROM `" . self::tableName() . "` " . $where->get() . " " . $limit->get());
		$this->db->commit();

        $collector = $this->factory->map($this->db->results());
        $collector->assertHasItem('Access denied! You do not have permission.');
        return $this->assert($collector->first());
    }

    public function assert(IPermission $permission):self{
        if($this->isRead() && !$permission->read()){
            throw new PermissionException('Access denied! You do not have permission to view or edit this resource.');
        }
		if($this->isWrite() && !$permission->write()){
            throw new PermissionException('Access denied! You do not have permission to add or create new resources.');
        }
		if($this->isEdit() && !$permission->edit()){
            throw new PermissionException('Access denied! You do not have permission to modify this resource.');
        }
		if($this->isDelete() && !$permission->delete()){
            throw new PermissionException('Access denied! You do not have permission to delete this resource.');
        }
        if(!$this->isRead() && !$this->isWrite() && !$this->isEdit() && !$this->isDelete()){
            throw new Exception('You need to first call select, insert, update or delete before you can call permission.');
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

    public static function tableName():string{
        return self::$tableName;
    }

    public static function setPermissionTableName(string $tableName):void{
        self::$tableName = $tableName;
    }

    public static function allowTableBypass(array $tableNames):void{
        self::$allowTableBypass = $tableNames;
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