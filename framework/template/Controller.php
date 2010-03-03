<?php
/**
 * Base controller
 *
 * @package template
 * @author Justin Palmer
 */
class Controller
{
	/**
	 * The layout that will be wrapped around the view.
	 *
	 * @var string
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
	 * @var string
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
	public $pr_cache_only = array();
	/**
	 * Array of actions that should NOT be cached.
	 *
	 * @var string
	 */
	public $pr_cache_except = array();
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
		$action = $this->pr_action;
		if($is_valid_type){
			$this->pr_do_cache = $this->needsCached();
			if($is_cached == false && $this->pr_do_cache){
				$this->$action();
			}elseif(!$this->pr_do_cache){
				$this->$action();
			}
		}else{
			$this->$action();
		}
	}
	/**
	 * Is the action in the cacheable actions?
	 *
	 * @return boolean
	 * @author Justin Palmer
	 **/
	public function needsCached()
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
	 * Render a different action.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function render($action)
	{
		# code...
	}
	/**
	 * Redirect to a different path;
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function redirect_to($path)
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
	public function respond_to()
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