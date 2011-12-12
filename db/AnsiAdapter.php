<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package db
 */
/**
 * Mysql adapter
 *
 * @todo shouldn't transaction, beginTransaction, commit, rollBack, savepoint all be in the
 * DatabaseAdapter as abstract and the DatabaseAdapter implement Transactional?
 *
 * @package db
 */
abstract class AnsiAdapter extends DatabaseAdapter implements Transactional
{
	static private $savepoints = array();
	static private $savepoint_counter = 0;

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function transaction($callback)
	{
		try{
			$this->beginTransaction();
			if(call_user_func($callback)){
				return $this->commit();
			}
			$this->rollBack();
		}catch(Exception $e){
			$this->rollBack();
		}
		return false;
	}

	/**
	 * Begin a transaction or set a savepoint
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function beginTransaction($savepoint=null)
	{
		//Figure out what savepoint query we are going to run depending on
		//if the user sends in a name.
		$savepoint_name = "sp" . self::$savepoint_counter;
		if($savepoint !== null)
			$savepoint_name = $savepoint;
		//Check the counter and decide to really start a transaction
		//or just add a savepoint.
		if(count(self::$savepoints) == 0) {
            $this->conn()->beginTransaction();
            $this->savepoint($savepoint_name);
        } else {
            $this->savepoint($savepoint_name);
        }
        self::$savepoint_counter++;
	}

	/**
	 * Set a savepoint
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function savepoint($name)
	{
		//Register the save point with the savepoints array
		self::$savepoints[] = $name;
		$this->conn()->exec('SAVEPOINT ' . $name);
	}

	/**
	 * Release a savepoint or commit
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function commit()
	{
		$savepoint = array_pop(self::$savepoints);
	    if(count(self::$savepoints) == 0) {
            $this->conn()->commit();
        } else {
            $this->conn()->exec("RELEASE SAVEPOINT " . $savepoint);
        }

	}

	/**
	 * Rollback to a savepoint or rollback
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function rollBack($savepoint=null)
	{
		$savepoint = array_pop(self::$savepoints);
		if(count(self::$savepoints) == 0) {
            $this->conn()->rollBack();
        } else {
            $this->conn()->exec("ROLLBACK TO SAVEPOINT " . $savepoint);
        }

	}

	/**
	 * Show columns
	 *
	 * We use this style instead of {query()} because up to 5.3.3.? there
	 * was a bug in the cli.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function showColumns($table_name)
	{
		$stmt = $this->conn()->prepare("SHOW COLUMNS FROM $table_name");
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	/**
	 * Truncate a table
	 *
	 * @return boolean
	 * @author Justin Palmer
	 **/
	public function truncate($table_name)
	{
		$stmt = $this->conn()->prepare("TRUNCATE TABLE $table_name");
		return $stmt->execute();
	}

	/**
	 * Show tables
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function showTables()
	{
		return $this->conn()->query("SHOW TABLES");
	}


	/**
	 * Build the create (insert) query for the adapter
	 *
	 * @return stdClass
	 * @author Justin Palmer
	 **/
	public function buildCreate(SqlBuilderHash $Hash)
	{
		$sql = 'INSERT INTO ' . $Hash->from() . ' (';
		$values = '';
		$params = array();
		foreach($Hash->props()->export() as $column => $value){
			if($value !== null && $value !== ''){
				if($value instanceof Expression){
					$sql .= $this->tick($column) . ',';
					$values .= $value . ',';
				}else{
					$sql .= $this->tick($column) . ',';
					$values .= '?,';
					$params[] = $value;
				}
			}
		}
		$sql = rtrim($sql, ',');
		$values = rtrim($values, ',');
		$sql .= ') VALUES (' . $values . ')';
		return (object) array('sql' => $sql, 'params'=>$params);
	}

	/**
	 * Build the read (select) query for the adapter
	 *
	 * @return stdClass
	 * @author Justin Palmer
	 **/
	public function buildRead(SqlBuilderHash $Hash)
	{
		$sql = 'SELECT ' . $Hash->select() . ' ' . $this->buildCount($Hash);
		$sql .= ' FROM ' . $Hash->from() .  ' ' . $Hash->join();
		if($Hash->where())
			$sql .= ' WHERE ' . $Hash->where();
		if($Hash->group())
			$sql .= ' GROUP BY ' . $Hash->group();
		if($Hash->having())
			$sql .= ' HAVING ' . $Hash->having();
		if(count($Hash->order()) > 0)
			$sql .= ' ORDER BY ' . implode(',', $Hash->order());
		if($Hash->limit())
			$sql .= $this->limit($Hash->offset(), $Hash->limit());
		return (object) array('sql' => $sql, 'params'=>$Hash->whereArgs());
	}

	/**
	 * Build the update (update) query for the adapter
	 *
	 * @return stdClass
	 * @author Justin Palmer
	 **/
	public function buildUpdate(SqlBuilderHash $Hash)
	{
		$sql = 'UPDATE ' . $Hash->from() . ' '. $Hash->join();
		$sql .= ' SET ';
		$params = array();
		foreach($Hash->props()->export() as $column => $value){
			if($value !== null && $value !== ''){
				if($value instanceof Expression){
					$sql .= $this->tick($column) . ' = ' . $value . ',';
				}else{
					$sql .= $this->tick($column) . ' = ?,';
					$params[] = $value;
				}
			}
		}
		$sql = rtrim($sql, ',');
		if($Hash->where())
			$sql .= ' WHERE ' . $Hash->where();
		return (object) array('sql' => $sql, 'params'=>array_merge($params,$Hash->whereArgs()));
	}

	/**
	 * Build the delete (delete) query for the adapter
	 *
	 * @return stdClass
	 * @author Justin Palmer
	 **/
	public function buildDelete(SqlBuilderHash $Hash)
	{
		$sql = 'DELETE FROM ' . $Hash->from();
		if($Hash->where())
			$sql .= ' WHERE ' . $Hash->where();
		if($Hash->limit())
			$sql .= $this->limit($Hash->offset(), $Hash->limit());
		return (object) array('sql' => $sql, 'params'=>$Hash->whereArgs());
	}

	/**
	 * Back tick the items passed in.
	 *
	 * @return array or string
	 * @author Justin Palmer
	 **/
	public function tick(/* items_to_tick */)
	{
		$args = func_get_args();
		if(sizeof($args) == 1){
			return '`' . array_shift($args) . '`';
		}
		$ret = array();
		foreach(array_values($args) as $item){
			$ret[] = "`$item`";
		}
		return $ret;
	}

	/**
	 * get the last insert id
	 *
	 * @return integer
	 * @author Justin Palmer
	 **/
	public function lastInsertId(){
		return $this->conn()->lastInsertId();
	}

 	/**
	 * return the limit string
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	protected function limit($offset, $limit)
	{
		$limit_s = ' LIMIT ';
		if($offset == '' && $limit > 0)
			return $limit_s . $limit;
		if($offset > 0 && $limit > 0)
			return $limit_s . $offset . ',' . $limit;
		return '';
	}

	/**
	 * Build a ansi count select.
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	protected function buildCount(Hash $Hash)
	{
		if(sizeof($Hash->count()) == 0)
			return '';
		$ret = '';
		if($Hash->select() != '')
			$ret .= ', ';
		foreach($Hash->count() as $count){
			$distinct = '';
			if($count->distinct)
				$distinct = 'DISTINCT ';
			$ret .= "COUNT($distinct$count->column) AS $count->as, ";
		}
		return rtrim($ret, ', ');
	}
}
