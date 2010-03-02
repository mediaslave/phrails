<?php
/**
 * TemplateCache class handles the hand off from the controller to the view
 * with cache support.
 *
 * @package template
 * @author Justin Palmer
 */
class TemplateCache extends Template
{
	private $cache_types = array('file');
	private $is_valid_cache_type = false;
	private $cache_type=null;
	/**
	 * Constructor
	 *
	 * @param $controller Controller
	 * @return TemplateCache
	 * @author Justin Palmer
	 **/
	public function __construct($controller)
	{
		parent::__construct($controller);
		$this->cache_type =  Registry::get('pr-cache-template');
	}
	/**
	 * The display method that including caching.
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public function display()
	{
		//Get the cache type.
		$cache_type = Registry::get('pr-cache-template');
		

		//If the cache type is null just return the template.
		//Or, if the cache_type is not one of the supported cache_types
		if($this->view_path === null  || 
			$cache_type === null || 
		   !$this->isValidCacheType($cache_type)){
			return parent::display();
		}
		//If it is a valid cache type then call the method and return the template view.
		$Cache = $this->factory($cache_type);
		$cached = $Cache->isCached();
		if($cached !== false){
			print 'from cache<br/><br/>';
			return $Cache->get();
		}
		//If it is not cached then display the non-cached version.
		else{
			$content = parent::display();
			$Cache->value = $content;
			$Cache->cache();
			return $content;
		}
	}
	/**
	 * Get the cache from the file if it exists.
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	private function factory($type)
	{
		$cache_type = ucfirst($type);
		$Object =  'Cache' . $type;
		$Cache = new $Object(sha1($this->view_path));
		return $Cache;
	}
	/**
	 * Is the cache type valid?
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function isValidCacheType($type)
	{
		return ($this->is_valid_cache_type = (in_array($type, $this->cache_types)));
	}
}