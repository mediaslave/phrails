<?php
/**
 * A Hash that sets an array with key/value pairs.
 * 
 * @todo if an array is passed to set as the $value turn it into an stdClass.
 * @package util
 */
class Hash
{
	/**
	 * An array to hold the key/value pairs.
	 *
	 * @author Justin Palmer
	 * @var array
	 */
	public $array;
	/**
	 * Constructor
	 *
	 * @param array $array 
	 * @return Hash
	 * @author Justin Palmer
	 */
	public function __construct(array $array=array())
	{
		$this->array = $array;								
	}
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
		$this->array[$key] = $value;
	}
	/**
	 * Get a value for the given key
	 *
	 * @param string $key 
	 * @return mixed
	 * @author Justin Palmer
	 */
	public function get($key)
	{
		return $this->_get($key);
	}
	
	/**
	 * Get
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function __get($key)
	{
		return $this->_get($key);
	}

	private function _get($key) {
		return ($this->isKey($key)) ? $this->array[$key] : null;
	}

	public function __set($key, $value) {
		$this->set($key, $value);
	}

	/**
	 * Is there a key in the Hash?
	 *
	 * @param string $key 
	 * @return boolean
	 * @author Justin Palmer
	 */
	public function isKey($key)
	{
		return (isset($this->array[$key]));
	}
	
	/**
	 * Remove element for the hash.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function remove($key)
	{
		if($this->isKey($key))
			unset($this->array[$key]);
	}
	/**
	 * Export the current Hash as an array
	 *
	 * @return array
	 * @author Justin Palmer
	 */
	public function export()
	{
		return $this->array;
	}
	/**
	 * Is the hash empty?
	 *
	 * @return boolean
	 * @author Justin Palmer
	 **/
	public function isEmpty()
	{
		return (empty($this->array)) ? true : false;
	}
}