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
