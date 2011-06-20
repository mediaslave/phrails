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
	public function set($key, $value, $alt_key=null)
	{
		if(!$this->isKey($key))
			$this->array[$key] = array();
		if($alt_key === null){
			$this->array[$key][] = $value;
		}else{
			$this->array[$key][$alt_key] = $value;
		}
	}
}