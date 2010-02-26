<?php

class Hash
{
	public $array;
	
	public function __construct(array $array=array())
	{
		$this->array = $array;								
	}
	
	public function set($key, $value)
	{
		$this->array[$key] = $value;
	}
	
	public function get($key)
	{
		if(!$this->isKey($key))
			throw new Exception('The key specified does not exist.');
		return $this->array[$key];
	}
	
	public function isKey($key)
	{
		return (isset($this->array[$key]));
	}
	
	public function export()
	{
		return $this->array;
	}
}