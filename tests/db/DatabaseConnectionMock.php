<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package tests
 */
/**
 * Description
 *
 * @package tests
 * @author Justin Palmer
 */
/**
 *
 *
 * @package db
 * @author Justin Palmer
 */
class DatabaseConnectionMock extends DatabaseConnection
{

	/**
	 * Hack to get the right connection information to the pdo construct.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	static public function connect()
	{
		return true;
	}

	/**
	 * Disconnect from the db
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	static public function disconnect(){}

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
