<?php
/**
* 
*/
class Registry	
{
	static $Hash;
	
	public function __construct()
	{
		self::$Hash = new Hash();
	}
	
	public static function set($key, $value)
	{
		self::init();
		self::$Hash->set($key, $value);
	}
	
	public static function get($key)
	{
		self::init();
		return self::$Hash->get($key);
	}
	
	public static function export()
	{
		self::init();
		return self::$Hash->array;
	}
	
	private function init()
	{
		if(self::$Hash === null){
			self::$Hash = new Hash();
		}
	}
}