<?php
/**
 * Base controller
 *
 * @todo Implement pr_from_cache.  If the template comes from the cache and the user
 * wants to use this feature let them.
 * @package template
 * @author Justin Palmer
 */
class Controller
{
	/**
	 * The layout that will be wrapped around the view.
	 *
	 * @var string or null
	 */
	public $pr_layout = 'application';
	/**
	 * The current controller.
	 *
	 * @var string
	 */
	public $pr_controller;
	/**
	 * The current action called
	 *
	 * @var string
	 */
	public $pr_action;
	/**
	 * The view type
	 *
	 * @var Hash
	 */
	public $pr_view_types;
	/**
	 * Should caching be performed on this method.
	 *	
	 * @var boolean
	 */
	public $pr_do_cache = false;
	/**
	 * Array of actions to cache.
	 *
	 * @var array
	 */
	protected $pr_cache_only = array();
	/**
	 * Array of actions that should NOT be cached.
	 *
	 * @var string
	 */
	protected $pr_cache_except = array();
	/**
	 * The before filters currently registered.
	 *
	 * @var string
	 */
	protected $pr_before_filters = array();
	/**
	 * The after filters currently registered.
	 *
	 * @var string
	 */
	protected $pr_after_filters = array();
	/**
	 * Was the template generated from the cache?
	 *
	 * @var boolean
	 */
	public $pr_from_cache_message = '<!-- pr_from_cache -->';
	/**
	 * Holds the flash for the current view.
	 * 
	 * @var string
	 */
	public $flash = '';
	/**
	 * Initialize some vars
	 *
	 * @return Controller
	 * @author Justin Palmer
	 */
	public function __construct()
	{
		$this->pr_controller = get_class($this);
		$this->pr_get = new Hash($_GET);
		$this->pr_post = new Hash($_POST);
		$this->pr_server = new Hash($_SERVER);
		$this->pr_view_types = new Hash(array('html'=>'html'));
	}
	
	/**
	 * Run the correct action if we don't need to cache
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function prRun($is_valid_type, $is_cached)
	{
		//Has the action ran?
		$action_ran = false;
		//Is the cache type valid?
		if($is_valid_type){
			//Set the controller var to whether or not we need to cache.
			$this->pr_do_cache = $this->needsCached();
			//if the file is cached AND
			//if we need to do the cache
			//Then run the action
			if($is_cached === false && $this->pr_do_cache){
				$this->prRunAction();
			//If we do not need to run the cache then just call the action.
			}elseif(!$this->pr_do_cache){
				$this->prRunAction();
			}
		//If the cache type is not valid then just run the action.
		}else{
			$this->prRunAction();
		}
	}
	/**
	 * Render a different action.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	protected function render($action)
	{
		$route = Registry::get('pr-route');
		$route->pr_action = $action;
		Registry::set('pr-route', $route);
	}
	/**
	 * Redirect to a different path;
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	protected function redirectTo($path)
	{
		$path = ltrim($path, '/');
		header('LOCATION:' . Registry::get('pr-domain-uri') . Registry::get('pr-install-path') . $path);
		exit();
	}
	/**
	 * Respond to the various formats
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	protected function respondTo()
	{
		$args = func_get_args();
		$set = array();
		foreach($args as $key => $value){
			if(is_array($value)){
				$set[key($value)] = current($value);
			}else{
				$set[$value] = $value;
			}
		}
		$this->pr_view_types = new Hash($set);
	}
	/**
	 * Run the action with the filters
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	private function prRunAction()
	{
		$action = $this->pr_action;
		$this->filter($this->pr_before_filters);
		$this->$action();
		$this->filter($this->pr_after_filters);
	}
	/**
	 * Run the before filters that are registered for an action.
	 *
	 * @param $array $pr_before_filter or $pr_after_filter
	 * @return void
	 * @author Justin Palmer
	 **/
	private function filter($array)
	{	
		$filters = array();
		if(isset($array[$this->pr_action])){
			$filters[] = $array[$this->pr_action];
		}
		if(isset($array['pr_global']))
			$filters = array_merge($filters, $array['pr_global']);
		if(is_array($filters)){
			foreach($filters as $method){
				$this->runFilter($method);
			}
		}else{
			$this->runFilter($filter);
		}
	}
	
	/**
	 * Run the filter specified or throw an exception
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	private function runFilter($filter)
	{
		//If the method exists lets call it.
		if(method_exists($this, $filter)){
			$this->$filter();
		//If it does not throw an exception.
		}else{
			throw new Exception("The filter: '$filter()' does not exist please create it in your controller.");
		}
	}
	
	/**
	 * Is the action in the cacheable actions?
	 *
	 * @return boolean
	 * @author Justin Palmer
	 **/
	private function needsCached()
	{
		//If the cache only and cache except array's are empty then return false.
		if(empty($this->pr_cache_only) && empty($this->pr_cache_except))
			return true;
		//If the action is in the ONLY then we need to cache.
		if(in_array($this->pr_action, $this->pr_cache_only))
			return true;
		//If it is in the EXCEPT we DON'T need to cache.
		if(in_array($this->pr_action, $this->pr_cache_except))
			return false;
		//All else fails don't cache.
		return true;
	}
	/**
	 * No route action.
	 * @return void
	 */
	public function prNoRoute(){
		$this->pr_action = 'prNoRoute';
	}
	/**
	 * No controller action.
	 * @return void
	 */
	public function prNoController(){
		$this->pr_action = 'prNoController';
	}
	/**
	 * No action action.
	 * @return void
	 */
	public function prNoAction(){
		$this->pr_action = 'prNoAction';
	}
	/**
	 * No view type to respond with
	 *
	 * @author Justin Palmer
	 **/
	public function prNoViewType()
	{
		$this->pr_action = 'prNoViewType';
	}
	
}