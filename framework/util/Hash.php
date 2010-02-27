<?php
/**
 * 
 */
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
		return ($this->isKey($key)) ? $this->array[$key] : null;
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