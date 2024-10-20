<?php
namespace src\database;

class Join {
    protected string $joints = '';
    protected Table $table;

    public function __construct(Table $table){
        $this->table = $table;
    }

    public function left($tableName, $column, $onTableName, $onColumn):self{
		$this->joints .= " LEFT JOIN `$tableName` ON `$onTableName`.`$onColumn` = `$tableName`.`$column`";
        return $this;
    }

    public function right($tableName, $column, $onTableName, $onColumn):self{
		$this->joints .= " RIGHT JOIN `$tableName` ON `$onTableName`.`$onColumn` = `$tableName`.`$column`";
        return $this;
    }

    public function inner($tableName, $column, $onTableName, $onColumn):self{
		$this->joints .= " INNER JOIN `$tableName` ON `$onTableName`.`$onColumn` = `$tableName`.`$column`";
        return $this;
    }

    public function full($tableName, $column, $onTableName, $onColumn):self{
		$this->joints .= " FULL JOIN `$tableName` ON `$onTableName`.`$onColumn` = `$tableName`.`$column`";
        return $this;
    }

    public function cross($tableName, $column, $onTableName, $onColumn):self{
		$this->joints .= " CROSS JOIN `$tableName` ON `$onTableName`.`$onColumn` = `$tableName`.`$column`";
        return $this;
    }

    public function natural($tableName, $column, $onTableName, $onColumn):self{
		$this->joints .= " NATURAL JOIN `$tableName` ON `$onTableName`.`$onColumn` = `$tableName`.`$column`";
        return $this;
    }

    public function reset():self{
		$this->joints = '';
        return $this;
    }

    public function hasJoints():bool{
        return !empty($this->get());
    }

    public function get():string{
        return $this->joints;
    }

    public function cursor():Table{
        return $this->table;
    }
}