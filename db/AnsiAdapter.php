<?php
/**
 * Mysql adapter
 *
 * @package db
 * @author Justin Palmer
 */				
abstract class AnsiAdapter extends DatabaseAdapter implements Transactional
{
	
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function transaction($callback)
	{
		$this->beginTransaction();
		try{
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
	 * undocumented function
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function beginTransaction($savepoint=null)
	{
		return $this->conn()->beginTransaction();
	}
	
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function commit()
	{
		return $this->conn()->commit();
	}
	
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function rollBack($savepoint=null)
	{
		return $this->conn()->rollBack();
	}
	
	/**
	 * Show columns
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function showColumns($table_name)
	{
		return $this->conn()->query("SHOW COLUMNS FROM `$table_name`", PDO::FETCH_OBJ)->fetchAll(PDO::FETCH_OBJ);
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
		$q_marks = '';
		$params = array();
		foreach($Hash->props()->export() as $column => $value){
			if($value !== null && $value !== ''){
				if($value instanceof Expression){
					$sql .= $this->tick($column) . ' = ' . $value . ',';
				}else{
					$sql .= $this->tick($column) . ',';
					$q_marks .= '?,';
					$params[] = $value;
				}
			}
		}
		$sql = rtrim($sql, ',');
		$q_marks = rtrim($q_marks, ',');
		$sql .= ') VALUES (' . $q_marks . ')';
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
		$sql = 'SELECT ' . $Hash->select() . ' ' . $this->buildCount($Hash) . ' 
				FROM ' . $Hash->from() .  ' ' . $Hash->join();
		if($Hash->where())
			$sql .= ' WHERE ' . $Hash->where();
		if($Hash->group())
			$sql .= ' GROUP BY ' . $Hash->group();
		if($Hash->having())
			$sql .= ' HAVING ' . $Hash->having();
		if($Hash->order())
			$sql .= ' ORDER BY ' . $Hash->order();
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
		$sql = 'UPDATE ' . $Hash->from() . ' '. $Hash->join() . 
		      ' SET ';
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
	 * @return void
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
	 * @return void
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
	 * undocumented function
	 *
	 * @return void
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
			$ret .= "COUNT($count->distinct $count->column) AS $count->as, ";
		}
		return rtrim($ret, ', ');
	}
}
