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
	/**
	 * Valid cache types
	 *
	 * @author Justin Palmer
	 * @var array
	 */
	private $cache_types = array('file');
	/**
	 * Is the current cache type a valid one.
	 *
	 * @author Justin Palmer
	 * @var boolean
	 */
	private $is_valid_cache_type = false;
	/**
	 * The current cache type that is trying to be processed.
	 *
	 * @author Justin Palmer
	 * @var string
	 */
	private $cache_type=null;
	/**
	 * The cache object to cache the template with.
	 *
	 * @author Justin Palmer
	 * @var Cache
	 */
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