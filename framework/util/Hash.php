<?php
/**
 * @todo if an array is passed to set as the $value turn it into an stdClass.
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
	
	public function export()
	{
		return $this->array;
	}
}