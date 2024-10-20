<?php
namespace src\database;

class OrderBy{
    protected string $orderBy = '';
    protected Table $table;

	public function __construct(Table $table){
		$this->table = $table;
	}

	public function desc($column, $tableName = null):self{
        $tableName = $tableName ?? $this->table->tableName();
		$this->orderBy .= ' ORDER BY `'.$tableName.'`.`'.$column.'` DESC ';
		return $this;
	}

	public function asc($column, $tableName = null):self{
        $tableName = $tableName ?? $this->table->tableName();
		$this->orderBy .= ' ORDER BY `'.$tableName.'`.`'.$column.'` ASC ';
		return $this;
	}

    public function reset():self{
		$this->orderBy = '';
        return $this;
    }

    public function hasOrderBy():bool{
        return !empty($this->get());
    }

    public function get():string{
        return $this->orderBy;
    }

    public function cursor():Table{
        return $this->table;
    }
}