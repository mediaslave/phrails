<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package template
 */
/**
 * class description
 *
 * @package template
 */
abstract class Cache
{
	/**
	 * The key to bring up the cache.
	 *
	 * @author Justin Palmer
	 * @var string
	 */
	public $key;
	/**
	 * The value for the current key
	 *
	 * @author Justin Palmer
	 * @var string
	 */
	public $value;
	/**
	 * The path of the current cache
	 *
	 * @author Justin Palmer
	 * @var string
	 */
	public $path;
	/**
	 * Is the request cached already?
	 *
	 * @author Justin Palmer
	 * @var boolean
	 */
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
