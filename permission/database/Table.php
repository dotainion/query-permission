<?php
namespace permission\database;
use tools\security\Setup;

class Table {
    protected string $tableName;
    protected string $query;
    protected string $union = '';
	protected Where $where;
	protected Column $cols;
	protected Join $join;
	protected OrderBy $orderBy;
	protected Pagination $pagination;
	protected ?Permission $permission = null;

	public function __construct(){
		$this->where = new Where($this);
		$this->cols = new Column($this);
        $this->join = new Join($this);
		$this->orderBy = new OrderBy($this);
		$this->pagination = new Pagination($this);
        $this->permission = new Permission($this);
	}

    public function toString():string{
        return $this->build()->getQuery();
    }

    public function tableName():string{
        return $this->tableName;
    }

    public function select(string $tableName, string $columns = '*'):self{
        $this->tableName = $tableName;
        $this->query = "SELECT $columns FROM `$this->tableName`";
		Setup::fireRepoSetObsover('select', $this);
        return $this;
    }

    public function insert(string $tableName):self{
        $this->tableName = $tableName;
        $this->query = "INSERT INTO `$this->tableName` ";
		Setup::fireRepoSetObsover('insert', $this);
        return $this;
    }

    public function update(string $tableName):self{
        $this->tableName = $tableName;
        $this->query = "UPDATE `$this->tableName` SET ";
		Setup::fireRepoSetObsover('update', $this);
        return $this;
    }

    public function delete(string $tableName):self{
        $this->tableName = $tableName;
        $this->query = "DELETE FROM `$this->tableName`";
		Setup::fireRepoSetObsover('delete', $this);
        return $this;
    }

    public function union():self{
        /*joint two select together eg: $this->select()->where()->union()->select()->where();*/
        $this->union = str_replace(';', '', $this->toString()) . ' UNION ';
        return $this;
    }

    public function unionAll():self{
        /*joint two select together eg: $this->select()->where()->unionAll()->select()->where();*/
        $this->union = str_replace(';', '', $this->toString()) . ' UNION ALL ';
        return $this;
    }

    public function drop(string $tableName) {
        $this->query = "DROP TABLE IF EXISTS `$tableName`";
        return $this;
    }

    public function truncate(string $tableName):self{
        $this->query = "TRUNCATE TABLE `$tableName`";
        return $this;
    }

    public function alias(string $columnName, string $newColumnName):self{
        $this->query = preg_replace(
            "/`$columnName`/",
            "`$columnName` AS `$newColumnName`",
            $this->query
        );
        return $this;
    }

    public function build():self{
        $this->permission->permission();
        if(strpos(strtoupper($this->query), 'INSERT INTO') === 0){
            $columns = $this->cols()->columns();
            $values = $this->cols()->values();
            $this->query .= "($columns) VALUES ($values)";
        }elseif(strpos(strtoupper($this->query), 'UPDATE') === 0) {
            $this->query .= implode(", ", array_map(function($column, $value){
				return "`$column` = $value";
			}, array_keys($this->cols()->list()), array_values($this->cols()->list())));
        }
		$this->query .= $this->join()->get();
		$this->query .= $this->where()->get();
		$this->query .= $this->orderBy()->get();
		$this->query .= $this->pagination()->get();

        //bind union/multiple query
        $this->union .= $this->query;
        $this->query = $this->union;

		$this->query .= ';';
        $this->union = '';
        return $this;
    }

    public function renameTableTo(string $newName):self{
        $this->query = "ALTER TABLE `$this->tableName` RENAME TO `$newName`";
        return $this;
    }

    public function renameColumn($oldName, $newName):self{
        $this->query = "ALTER TABLE `$this->tableName` RENAME COLUMN `$oldName` TO `$newName`";
        return $this;
    }

    public function reset():self{
		$this->cols()->reset();
		$this->where()->reset();
		$this->pagination()->reset();
		$this->orderBy()->reset();
		$this->join()->reset();
        return $this;
    }

    public function getQuery():string{
        return $this->query;
    }

    public function column($name, $value):self{
		$this->cols()->column($name, $value);
        return $this;
    }

    public function where():Where{
        return $this->where;
    }

    public function cols():Column{
        return $this->cols;
    }

    public function orderBy():OrderBy{
        return $this->orderBy;
    }

	public function pagination():Pagination{
		return $this->pagination;
	}

	public function join():Join{
		return $this->join;
	}

	public function permission():Permission{
		return $this->permission;
	}
}