<?php
namespace src\database;

class Column {
    protected array $columns = [];
    protected Table $table;

    public function __construct(Table $table){
        $this->table = $table;
    }

    public function column($name, $value):self{
        if (is_null($value)) {
            $this->columns[$name] = 'NULL';
        } elseif (is_numeric($value)) {
            $this->columns[$name] = $value;
        } else {
            $this->columns[$name] = "'" . addslashes($value) . "'";
        }
        return $this;
    }

    public function reset():self{
        $this->columns = [];
        return $this;
    }

    public function list():array{
        return $this->columns;
    }

    public function columns():string{
        return implode(", ", array_map(fn($column) => "`$column`", array_keys($this->columns)));
    }

    public function values():string{
        return implode(", ", array_map(fn($value) => $value, array_values($this->columns)));
    }

    public function cursor():Table{
        return $this->table;
    }
}