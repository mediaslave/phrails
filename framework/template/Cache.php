<?php
/**
 * Interface for caching
 *
 * @package template
 * @author Justin Palmer
 **/
abstract class Cache
{
	public $key;
	public $value;
	public $path;
	protected $is_cached = false;
	/**
	 * Constructor
	 *
	 * @return Cache
	 * @author Justin Palmer
	 **/
	public function __construct($key, $value=null)
	{
		$this->key = $key;
		$this->value = $value;
	}
	/**
	 * Is the cache available?
	 *
	 * @return boolean
	 * @author Justin Palmer
	 **/
	public function isCached(){
		return $this->is_cached;
	}
	/**
	 * Get the cached file.
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	abstract public function get();
	
	/**
	 * Cache the current value.
	 *
	 * @return boolean
	 * @author Justin Palmer
	 **/
	abstract public function cache();
} // END class Cache