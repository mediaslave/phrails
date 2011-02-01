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
}
