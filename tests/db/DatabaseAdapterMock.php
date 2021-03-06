<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package tests
 */
/**
 * Description
 *
 * @package tests
 */
/**
 * Database adapter
 *
 * @package db
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
