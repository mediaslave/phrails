<?php
/**
* A object to deal with various framework properties.
* @package util 
*/
class Registry	
{
	/**
	 * A Hash to store the properties
	 *
	 * @author Justin Palmer
	 * @var Hash
	 */
	static $Hash;
	/**
	 * Constructor
	 *
	 * @author Justin Palmer
	 * @return Registry
	 */
	public function __construct()
	{
		self::$Hash = new Hash();
	}
	/**
	 * Set a property
	 *
	 * @param string $key 
	 * @param mixed $value 
	 * @return void
	 * @author Justin Palmer
	 */
	public static function set($key, $value)
	{
		self::init();
		$v = $value;
		if(is_array($value)){
			$v = new stdClass;
			foreach($value as $vkey => $value){
				//print $value . '<br/>';
				$v->$vkey = $value;
			}
		}
		self::$Hash->set($key, $v);
	}
	/**
	 * Get a property
	 *
	 * @param string $key 
	 * @return mixed
	 * @author Justin Palmer
	 */
	public static function get($key)
	{
		self::init();
		return self::$Hash->get($key);
	}
	/**
	 * Export the current properties
	 *
	 * @return array
	 * @author Justin Palmer
	 */
	public static function export()
	{
		self::init();
		return self::$Hash->export();
	}
	/**
	 * Initiate the hash if it does not exist.
	 *
	 * @return void
	 * @author Justin Palmer
	 */
	private static function init()
	{
		if(self::$Hash === null){
			self::$Hash = new Hash();
		}
	}
}