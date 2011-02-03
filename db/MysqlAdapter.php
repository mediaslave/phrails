<?php
/**
 * Mysql adapter
 *
 * @package db
 * @author Justin Palmer
 */				
class MysqlAdapter extends DatabaseAdapter
{
	
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function transactional($callback)
	{
		$this->conn()->beginTransaction();
		try{
			if(call_user_func($callback)){
				return $this->conn()->commit();
			}
			$this->conn()->rollBack();
		}catch(Exception $e){
			$this->conn()->rollBack();
		}
		return false;
	}
	
	/**
	 * Show columns
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function showColumns($table_name)
	{
		return $this->conn()->query("SHOW COLUMNS FROM `$table_name", PDO::FETCH_OBJ);
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
		foreach($Hash->props()->export() as $key => $value){
			$sql .= $this->tick($key) . ',';
			$q_marks .= '?,';
			$params[] = $value;
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
	 * Store the column structure in a hash.
	 * 
	 * @todo move to base DatabaseAdapter and take a third parameter for the field to make into the Hash key.
	 *
	 * @return array
	 * @author Justin Palmer
	 **/
	public function cacheColumns($class_name, $table_name)
	{
		//Hold the columns from the db to make sure properties, rules and relationships set actually exist.
		if($this->ColumnsCache->isKey($class_name)){
			return $this->ColumnsCache->get($class_name);
		}else{
			$cols = $this->showColumns($table_name)->fetchAll(PDO::FETCH_OBJ);
			$cache = new Hash();
			foreach($cols as $column){
				$cache->set($column->Field, $column);
			}
			$this->ColumnsCache->set($class_name, $cache);
			return $cache;	
		}
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
