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
		   !in_array($cache_type, $this->cache_types)){
			return parent::display();
		}
		//If it is a valid cache type then call the method and return the template view.
		$cache_type = ucfirst($cache_type);
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
		$Object =  'Cache' . $type;
		$Cache = new $Object(sha1($this->view_path));
		return $Cache;
	}
}