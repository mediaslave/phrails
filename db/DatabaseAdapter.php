<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
 */

/**
* @package db
* @author Justin Palmer
*/
abstract class DatabaseAdapter
{
	/**
	 * Constants of the different types of build methods build_<type>
	 */
	const CREATE = 'create';
	const READ   = 'read';
	const UPDATE = 'update';
	const DELETE = 'delete';

	/**
	 * Database connection
	 *
	 * @author Justin Palmer
	 * @var PDO
	 */
	private $conn = null;

	/**
	 * Hash table to store table column information
	 *
	 * @author Justin Palmer
	 * @var Hash
	 */
	static protected $ColumnsCache = null;
	/**
	 * Constructor
	 *
	 * @return Mysql
	 * @author Justin Palmer
	 **/
	public function __construct()
	{
		$this->conn = DatabaseConnection::connect();
		if(!(self::$ColumnsCache instanceof Hash))
			self::$ColumnsCache = new Hash();
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
	 * Truncate a table
	 *
	 * @return boolean
	 * @author Justin Palmer
	 **/
	abstract public function truncate($table_name);

	/**
	 * Store the columns for a table.
	 *
	 * @return Hash
	 * @author Justin Palmer
	 **/
	abstract public function cacheColumns($class_name, $table_name, $forceReload=false);

	/**
	 * Build the create (insert) query for the adapter
	 *
	 * @param SqlBuilderHash
	 * @return stdClass
	 * @author Justin Palmer
	 **/
	abstract public function buildCreate(SqlBuilderHash $Hash);

	/**
	 * Build the read (select) query for the adapter
	 *
	 * @param SqlBuilderHash
	 * @return stdClass
	 * @author Justin Palmer
	 **/
	abstract public function buildRead(SqlBuilderHash $Hash);

	/**
	 * Build the update (update) query for the adapter
	 *
	 * @param SqlBuilderHash
	 * @return stdClass
	 * @author Justin Palmer
	 **/
	abstract public function buildUpdate(SqlBuilderHash $Hash);

	/**
	 * Build the delete (delete) query for the adapter
	 *
	 * @param SqlBuilderHash
	 * @return stdClass
	 * @author Justin Palmer
	 **/
	abstract public function buildDelete(SqlBuilderHash $Hash);

	/**
	 * Back tick the items needed
	 *
	 * @return array
	 * @author Justin Palmer
	 **/
	abstract public function tick(/* items_to_tick */);

	/**
	 * The limit string for a query.
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	abstract protected function limit($offset, $limit);

	/**
	 * get the last insert id
	 *
	 * @return integer
	 * @author Justin Palmer
	 **/
	abstract public function lastInsertId();
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
