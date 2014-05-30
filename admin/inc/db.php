<?php
class db {
	protected $db_name = 'stats';
	protected $db_user, $db_pass;
	protected $db_host = 'localhost';
	protected $connection = null;
	protected static $instance;
    var $dbg=0;

	public function __construct() {
		$this->setuserdata('root','pwd');
		$this->connect();
	}

	public static function &getInstance() {
        if (self::$instance === null)
            self::$instance = new self();
        return self::$instance;
    }

	public function setuserdata($name, $pass) {
		$this->db_user = $name;
		$this->db_pass = $pass;
	}

	public function mysql_close() {
		$this->connection->close();
	}

	public function secure($in){
		return mysqli_real_escape_string($this->connection, $in);
	}

	public function connect() {
		$connection = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
		if (!mysqli_connect_errno()) {
			$connection->set_charset("utf8");
			$this->connection = &$connection;
        
			return true;
		} else {
			trigger_error("Невозможно подключиться к базе данных {$this->db_name}", E_USER_ERROR);
            die('Ведутся техические работы.');
			return false;
		}
	}

	public function processRowSet($rowSet, $singleRow = false) {
        $resultArray = array();
        while ($row = mysqli_fetch_assoc($rowSet)) {
            array_push($resultArray, $row);
        }
        
		if($singleRow === true) {
			return $resultArray[0];
		} else
			return $resultArray;
	}

    private function debug($sql,$type){
    	// 
    }

    // SELECT
    public function select($table, $where, $limit = 0,$show=0) {
        $limit_sql = ($limit != 0) ? ' LIMIT '.$limit : '';
        $sql = "SELECT * FROM {$table} WHERE {$where}{$limit_sql}";
        $this->debug(null,1);
        $monit_start_time = microtime(1);
        $result = $this->connection->query($sql);
        if ($result) {
            $num = $result->num_rows;
        } else {
            $num = 0;
        }
        $this->debug($sql,0);

        if($num == 1 && $limit == 1) {
            return $this->processRowSet($result, true);
        } elseif($num == 0) {
            return false;
        } else {
            return $this->processRowSet($result);
        }
    }

	// SQL
	public function sql($sql, $is_select = false) {
        $this->debug(null,1);
		$result = $this->connection->query($sql);
        $this->debug($sql,0);
        if ($is_select) {
            if ($result) {
                $num = $result->num_rows;
            } else {
                $num = 0;
            }
            if($num == 0) {
                return false;
            } else {
                return $this->processRowSet($result);
            }
        } else
		    return $result;
	}

	public function select_distinct($columns, $table, $where, $limit = 0) {
        $g=mc::get("sd.".md5($columns.$table.$where.$limit));
        if(!$g){
            $limit_sql = ($limit != 0) ? ' LIMIT '.$limit : '';
            $this->debug(null,1);
            $sql = "SELECT DISTINCT {$columns} FROM {$table} WHERE {$where}{$limit_sql}";
            $this->debug($sql,0);
            $result = $this->connection->query($sql) or die('DIstinct error');
            $num = $result->num_rows;

            if($num == 1 && $limit == 1) {
                //return $this->processRowSet($result, true);
                $z=$this->processRowSet($result,true);
                mc::set("sd.".md5($columns.$table.$where.$limit), serialize($z), MEMCACHE_COMPRESSED, 150);
                return $z;
            } elseif($num == 0) {
                return false;
            } else {
                $z=$this->processRowSet($result);
                mc::set("sd.".md5($columns.$table.$where.$limit), serialize($z), MEMCACHE_COMPRESSED, 150);
                return $z;
            }
        }else{
            return unserialize($g);
        }
	}

	// COUNT
	public function count($table, $where) {
            $this->debug(null,1);
            $sql = "SELECT COUNT(*) FROM {$table} WHERE {$where}";
            $this->debug($sql,0);
            $result = $this->connection->query($sql) or die('Ошибка подсчета');
            $r = $result->fetch_row();
            return $r[0];
	}

	// SUM
	public function sum($field, $table, $where) {
		$field = mysqli_real_escape_string($this->connection, $field);
        $this->debug(null,1);
		$sql = "SELECT SUM({$field}) FROM {$table} WHERE {$where}";
        $this->debug($sql,0);
		$result = $this->connection->query($sql) or die('Ошибка подсчета');
		$r = $result->fetch_row();
		return $r[0];
	}

	public function avg($field, $table, $where) {
		$field = mysqli_real_escape_string($this->connection, $field);
        $this->debug(null,1);
		$sql = "SELECT AVG({$field}) FROM {$table} WHERE {$where}";
        $this->debug($sql,0);
		$result = $this->connection->query($sql) or die('Ошибка подсчета');
		$r = $result->fetch_row();
		return $r[0];
	}

	// INSERT
	public function insert($data, $table) {
		$columns = "";
		$values = "";
		
		foreach ($data as $column => $value) {
			$value = stripcslashes(mysqli_real_escape_string($this->connection, $value));
			$columns .= ($columns == "") ? "" : ", ";
			$columns .= $column;
			$values .= ($values == "") ? "" : ", ";
			$values .= "'".$value."'";
		}
        $this->debug(null,1);
		$sql = "insert into $table ($columns) values ($values)";
        $this->debug($sql,0);
		$this->connection->query($sql) or die($sql.' - Insert error');

		//Выводит ID пользователя в БД.
		return mysqli_insert_id($this->connection);
	}

	// UPDATE
	public function update($data, $table, $where) {
		foreach ($data as $column => $value) {
			$value = stripcslashes(mysqli_real_escape_string($this->connection, $value));
            $this->debug(null,1);
			$sql = "UPDATE $table SET $column = '$value' WHERE $where";
            $this->debug($sql,0);
			$this->connection->query($sql) or die($sql.' - Произошла ошибка, данные не сохранились. Попробуйте ещё раз.');
		}
		return true;
	}
}
?>