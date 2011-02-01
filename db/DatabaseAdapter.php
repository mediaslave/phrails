<?php
/**
 * Database adapter
 *
 * @package db
 * @author Justin Palmer
 */				
abstract class DatabaseAdapter
{
	/**
	 * Database connection
	 * 
	 * @author Justin Palmer
	 * @var PDO
	 */
	private $conn = null;
	/**
	 * Constructor
	 *
	 * @return Mysql
	 * @author Justin Palmer
	 **/
	public function __construct()
	{
		$this->conn = DatabaseConnection::connect();
	}
	
	/**
	 * Show columns
	 *
	 * @return stdClass
	 * @author Justin Palmer
	 **/
	abstract public function showColumns($table_name);
	
	/**
	 * Show the tables
	 *
	 * @return stdClass
	 * @author Justin Palmer
	 **/
	abstract public function showTables();
	
	
	/**
	 * Get the correct Adapter
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	static public function get()
	{
		$type = DatabaseConfiguration::getType();
		$AdapterClass = $type . 'Adapter';
		return new $AdapterClass;
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
