<?php
/**
 * Base controller
 *
 * @todo Implement pr_from_cache_message.  This really needs re-thought.  There should be an easier way.
 * 
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
	 * Filters object
	 *
	 * @var Filters
	 */
	protected $pr_filters = null;
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
		$this->pr_params = new Hash($_POST += $_GET += $_SERVER);
		$this->pr_view_types = new Hash(array('html'=>'html'));
		$this->pr_filters = new Filters($this);
		if(isset($_SESSION['pr_flash'])){
			$this->flash = $_SESSION['pr_flash'];
			unset($_SESSION['pr_flash']);
		}
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
		$route->action = $action;
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
		if($this->flash !== ''){
			$_SESSION['pr_flash'] = $this->flash;
		}
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
			//var_dump($value);
			if(is_array($value)){
				$set[key($value)] = array_shift($value);
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
		$this->filters()->run(Filters::before);
		$this->filters()->run(Filters::around);
		$this->$action();
		$this->filters()->run(Filters::around);
		$this->filters()->run(Filters::after);
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
	/**
	 * Return the filters object.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	protected function filters()
	{
		return $this->pr_filters;
	}
	/**
	 * Return request params
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	protected function params($key)
	{
		$ret = null;
		$var = $this->pr_params->get($key);
		if(is_array($var)){
			$ret = array();
			foreach($var as $key => $value){
				$ret[$key] = trim(stripslashes($value));
			}
		}else{
			$ret = trim(stripslashes($var));
		}
		return $ret;
	}
}