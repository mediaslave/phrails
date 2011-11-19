<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
 */
/**
 * class description
 *
 * @package util
 * @author Justin Palmer
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
	 * Just as set, but makes sure it is an array and adds to the array if need be.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public static function setInArray($key, $value)
	{
		self::init();
		$array = self::$Hash->get($key);
		if($array === null) $array = array();
		if(is_array($value)){
			$array = array_merge($array, $value);
		}else{
			$array[] = $value;
		}
		self::$Hash->set($key, $array);
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
