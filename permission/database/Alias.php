<?php
namespace permission\database;

use Exception;

class Alias {
    protected array $columns = [];
    protected ?array $stash = null;
    protected Table $table;

    public function __construct(Table $table){
        $this->table = $table;
    }

    public function column(string $columnName, ?string $tableName=null):self{
        $tableName = $tableName ?? $this->table->tableName();
        if(!empty($this->stash)){
            throw new Exception('After calling function column() you need to call function as().');
        }
        $this->stash = [
            'table' => $tableName,
            'column' => $columnName,
        ];
        return $this;
    }

    public function as(string $newColumnName):self{
        $this->columns[] = [
            ...$this->stash,
            'newColumn' => $newColumnName
        ];
        $this->stash = null;
        return $this;
    }

    public function reset():self{
		$this->columns = [];
        return $this;
    }

    public function hasAlias():bool{
        return !empty($this->columns);
    }

    public function get():string{
        if(!$this->hasAlias()){
            return $this->table->getQuery();
        }
        $query = '';
        foreach($this->columns as $col){
            ($query !== '') && $query .= ', ';
            $table = $col['table'];
            $column = $col['column'];
            $newColumn = $col['newColumn'];
            $query .= "`$table`.`$column` AS `$newColumn`";
        }
        if(str_contains($this->table->getQuery(), '*')){
            return str_replace('*', "*, $query", $this->table->getQuery());
        }
        $tableName = $this->table->getQuery();
        //this link is added but not tested
        //the idea is to find the table and replace it with the query while adding back the table name
        //the reason we are doin this is becasue thre is not * but indivedual column names
        return str_replace($tableName, ",$query $tableName", $this->table->getQuery());
    }

    public function cursor():Table{
        return $this->table;
    }
}