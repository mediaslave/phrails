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
	public $Cache;
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
		if($this->isValidCacheType())
			$this->factory();
	}
	/**
	 * The display method that including caching.
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public function display()
	{		
		//var_dump($this->Controller);
		//If the cache type is null just return the template.
		//Or, if the cache_type is not one of the supported cache_types
		if($this->view_path === null  || 
			(!$this->Controller->pr_do_cache) ||
		   (!$this->isValidCacheType())
		  )
		{
			return parent::display();
		}
		//If it is a valid cache type then call the method and return the template view.
		$cached = $this->Cache->isCached();
		if($cached !== false){
			print 'from cache<br/><br/>';
			return $this->Cache->get();
		}
		//If it is not cached then display the non-cached version.
		else{
			$content = parent::display();
			$this->Cache->value = $content;
			$this->Cache->cache();
			return $content;
		}
	}
	/**
	 * Get the cache from the file if it exists.
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	private function factory()
	{
		$type = ucfirst($this->cache_type);
		$Object =  'Cache' . $type;
		$this->Cache = new $Object(sha1($this->view_path));
		return $this->Cache;
	}
	/**
	 * Is the cache type valid?
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function isValidCacheType()
	{
		return ($this->is_valid_cache_type = (in_array($this->cache_type, $this->cache_types)));
	}
}