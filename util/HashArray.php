<?php
/**
 * A HashArray allows you to add multiple items to a key. Get will then return an array of items.
 * 
 * @todo if an array is passed to set as the $value turn it into an stdClass.
 * @package util
 */
class HashArray extends Hash
{

	/**
	 * Set a key/value pair
	 *
	 * @param string $key 
	 * @param mixed $value 
	 * @return void
	 * @author Justin Palmer
	 */
	public function set($key, $value)
	{
		if(!$this->isKey($key))
			$this->array[$key] = array();
		$this->array[$key][] = $value;
	}
}