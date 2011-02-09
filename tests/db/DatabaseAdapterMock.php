<?php
/**
 * Database adapter
 *
 * @package db
 * @author Justin Palmer
 */				
abstract class DatabaseAdapterMock extends DatabaseAdapter
{

	/**
	 * Constructor
	 *
	 * @return Mysql
	 * @author Justin Palmer
	 **/
	public function __construct()
	{
		$this->conn = DatabaseConnectionMock::connect();
		if(!(self::$ColumnsCache instanceof Hash))
			self::$ColumnsCache = new Hash();
	}
	/**
	 * Get the correct Adapter
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	static public function get()
	{
		return new DatabaseAdapterMock;
	}
	/**
	 * Get the connection
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function conn()
	{
		return $this->conn;
	}
}
