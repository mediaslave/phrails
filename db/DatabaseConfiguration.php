<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
 * @package db
 */

/**
* @package db
* @author Justin Palmer
*/
class DatabaseConfiguration
{
	/**
	 * Implemented drivers
	 *
	 * @author Justin Palmer
	 * @var array
	 */
	static private $types = array('mysql'=>'Mysql');

	/**
	 * The configuration for the database
	 *
	 * @author Justin Palmer
	 * @var stdClass
	 */
	static private $Config = null;

	/**
	 * Add a type to the available types.  You must create your own driver and adapter when doing this.
	 *
	 * @param string $name - The key of the type array
	 * @param string $class_prefix - The prefix of the class ($class_prefix . 'Adapter', $class_prefix . 'Driver')
	 * @return void
	 * @author Justin Palmer
	 **/
	public function addType($name, $class_prefix)
	{
		self::$types[$name] = $class_prefix;
	}

	/**
	 * Get a var from the config
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	static public function get($var)
	{
		$Config = self::getConfig();
		if(isset($Config->$var))
			return $Config->$var;
		throw new Exception('There is no DatabaseConfiguration of: ' . $var);
	}

	/**
	 * Confirm that the driver is a valid driver.
	 *
	 * @return boolean
	 * @author Justin Palmer
	 **/
	static public function getType()
	{
		$Config = self::getConfig();
		if(!array_key_exists($Config->driver, self::$types))
			throw new UnknownDatabaseTypeException($Config->driver);
		return self::$types[$Config->driver];
	}


	/**
	 * Get the config for the db.
	 *
	 * @return stdClass
	 * @author Justin Palmer
	 **/
	static public function getConfig()
	{
		$Config = self::$Config;
		if(self::$Config === null)
			self::$Config = $Config = Registry::get('pr-db-config');
		return $Config;
	}

}
