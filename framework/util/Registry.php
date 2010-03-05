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
	
	public static function get($key)
	{
		self::init();
		return self::$Hash->get($key);
	}
	
	public static function export()
	{
		self::init();
		return self::$Hash->export();
	}
	
	private static function init()
	{
		if(self::$Hash === null){
			self::$Hash = new Hash();
		}
	}
}