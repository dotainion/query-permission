<?php
namespace permission\database;

class Where {
    protected array $conditions = [];
    protected Table $table;

    public function __construct(Table $table){
        $this->table = $table;
    }

    public function where($column, $operator, $value, $tableName = null):self{
        $tableName = $tableName ?? $this->table->tableName();
        if(is_array($value)){
            $vals = implode(', ', array_map(fn($val) => '"' . addslashes($val) . '"', $value));
            $this->conditions[] = "`$tableName`.`$column` IN ($vals)";
        }elseif(is_null($value)){
            $this->conditions[] = "`$tableName`.`$column` $operator NULL";
        }else{
            $this->conditions[] = "`$tableName`.`$column` $operator '" . addslashes($value) . "'";
        }
        return $this;
    }

    public function reset():self{
        $this->conditions = [];
        return $this;
    }

    public function like($column, $value, $tableName = null):self{
        $tableName = $tableName ?? $this->table->tableName();
        $this->conditions[] = "`$tableName`.`$column` LIKE '%" . addslashes($value) . "%'";
        return $this;
    }

    public function between($column, $from, $to, $tableName = null):self{
        $tableName = $tableName ?? $this->table->tableName();
        $this->conditions[] = "`$tableName`.`$column` BETWEEN '" . addslashes($from) . "' AND '" . addslashes($to) . "'";
        return $this;
    }

	public function eq($column, $value, $tableName = null):self{
		$this->where($column, '=', $value, $tableName);
        return $this;
	}

	public function isNull($column, $tableName = null):self{
		$this->where($column, 'IS', null, $tableName);
        return $this;
	}

	public function isNotNull($column, $tableName = null):self{
		$this->where($column, 'IS NOT', null, $tableName);
        return $this;
	}

	public function lessThan($column, $value, $tableName = null):self{
		$this->where($column, '<', $value, $tableName);
        return $this;
	}

	public function moreThan($column, $value, $tableName = null):self{
		$this->where($column, '>', $value, $tableName);
        return $this;
	}

	public function lessThanOrEqualTo($column, $value, $tableName = null):self{
		$this->where($column, '<=', $value, $tableName);
        return $this;
	}

	public function moreThanOrEqualTo($column, $value, $tableName = null):self{
		$this->where($column, '>=', $value, $tableName);
        return $this;
	}

    public function hasWhere():bool{
        return !empty($this->get());
    }

    public function get():string{
        if (empty($this->conditions)) {
            return '';
        }
        return ' WHERE ' . implode(' AND ', $this->conditions);
    }

    public function cursor():Table{
        return $this->table;
    }
}