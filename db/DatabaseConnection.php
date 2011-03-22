<?php
/**
 * 
 * 
 * @package db
 * @author Justin Palmer
 */				
class DatabaseConnection
{	
	/**
	 * The pdo object
	 * 
	 * @author Justin Palmer
	 * @var PDO
	 */
	static protected $pdo = null;
	
	/**
	 * Hack to get the right connection information to the pdo construct.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	static public function connect()
	{
		if(self::$pdo instanceof PDO)
			return self::$pdo;
		try{
			$Driver = DatabaseConfiguration::getType();
			$DriverClass = $Driver . 'Driver';
			self::$pdo = new $DriverClass;
			return self::$pdo;
		} catch (PDOException $e) {
			throw new DatabaseConnectionException($e->getMessage());
		}
	}
	
	/**
	 * Disconnect from the db
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	static public function disconnect()
	{
		self::$pdo = null;
	}
	
	/**
	 * Destruct will disconnect from the database
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function __destruct()
	{
		self::disconnect();
	}
}