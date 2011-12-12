<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package template
 */
/**
 * class description
 *
 * @todo cache message is hard coded, should be able to change this.
 * @package template
 */
class CacheFile extends Cache
{
	/**
	 * Constructor
	 *
	 * @return CacheFile
	 * @author Justin Palmer
	 **/
	public function __construct($key, $value=null)
	{
		parent::__construct($key, $value);
	}

	/**
	 * Is the cache available?
	 *
	 * @return boolean
	 * @author Justin Palmer
	 **/
	public function isCached()
	{
		$this->config = Registry::get('pr-cache-config');
		if($this->config === null)
			throw new Exception('Please set up the cache config');

		if(parent::isCached())
			return true;

		//Set the file path.
		$this->path = $this->config->path . '/' . $this->key;
		//Does the file exist
		if(!file_exists($this->path))
			return false;

        //Make sure we can get the time of the file
		if(!($filemtime = filemtime($this->path)))
			return false;

		//Is the file cached with an appropriate ttl?
		if(($filemtime + $this->config->ttl) < time()) {
			@unlink($this->path);
			return false;
		}
		else {
		    /**
		     * Cache the results of this is_cached() call.  Why?  So
		     * we don't have to double the overhead for each template.
		     * If we didn't cache, it would be hitting the file system
		     * twice as much (file_exists() & filemtime() [twice each]).
		     */
		    $this->is_cached = true;
		    return true;
		}
	}
	/**
	 * Get the cached file.
	 *
	 * @return string or false
	 * @author Justin Palmer
	 **/
	public function get()
	{
		if($this->is_cached) {
		    $fp = fopen($this->path, 'r');
		    $contents = fread($fp, filesize($this->path));
		    fclose($fp);
		    return $contents;
		}
		else{
			$this->cache();
		    return false;
		}
	}

	/**
	 * Cache the current value.
	 *
	 * @return boolean
	 * @author Justin Palmer
	 **/
	public function cache()
	{
		$boolean = true;
		// Write the cache
	    if($fp = fopen($this->path, 'x')) {
	        $boolean = fwrite($fp, str_replace('<!-- pr_from_cache -->', '<div class="from-cache-message">This template is generated from the cache and the data may be out of date.</div>', $this->value));
	        fclose($fp);
			//chmod($filename, '777');
	    }else{
			throw new Exception('Could not write to the cache file.');
		}
		return $boolean;
	}
} // END class CacheFile
