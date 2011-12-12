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
	 * The view path.  If you want to change the path to which the views live on
	 *
	 * @var string
	 */
	public $pr_view_path;
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
	 * @var ControllerFilters
	 */
	protected $pr_filters = null;
	/**
	 * Was the template generated from the cache?
	 *
	 * @var boolean
	 */
	public $pr_from_cache_message = '<!-- pr_from_cache -->';
	/**
	 * Holds the flash object for the current view.
	 *
	 * @var HashArray
	 */
	public $flash = null;
	/**
	 * This is so that you can set static vars that will be public upon instantiation.
	 *
	 * Helpful for setting controller vars when in an initializer.
	 *
	 * @var Hash
	 */
	private static $public_vars = null;
	/**
	 * The view type
	 *
	 * @var Hash
	 */
	public $pr_view_types;

	/**
	 * Initialize some vars
	 *
	 * @return Controller
	 * @author Justin Palmer
	 */
	public function __construct()
	{
		$this->pr_controller = get_class($this);
		$this->pr_request = new Request;
		Registry::set('pr-request', $this->pr_request);
		$this->pr_filters = new ControllerFilters($this);
		$this->pr_view_types = new Hash(array('html'=>'html'));
		$this->flash = new HashArray();
		if($this->pr_request->session('pr_flash') instanceof HashArray){
			$this->flash->array = $this->pr_request->session('pr_flash')->export();
			unset($_SESSION['pr_flash']);
		}
		$this->setStaticPublicVars();
	}

	/**
	 * Run the correct action if we don't need to cache
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function prRun($is_valid_type, $is_cached)
	{
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
		if(!$this->flash->isEmpty()){
			$this->pr_request->session('pr_flash', $this->flash);
		}
		if($path != '/') $path = ltrim($path, '/');
		$install_path = str_replace("/", "\/", Registry::get('pr-install-path'));
		header('LOCATION:' . Registry::get('pr-domain-uri')  . '/' . preg_replace("%^" . $install_path . "%", '', $path, 1));
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
		//var_dump($args);
		foreach($args as $key => $value){
			//var_dump($value);
			//var_dump($key);
			if(is_array($value)){
				//$set[key($value)] = array_shift($value);
				$set[key($value)] = (object) $value;
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
		$this->filters()->run(ControllerFilters::before);
		$this->filters()->run(ControllerFilters::around);
		$this->$action();
		$this->filters()->run(ControllerFilters::around);
		$this->filters()->run(ControllerFilters::after);
		$this->pr_num_queries = ActiveRecord::$num_queries;
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
	 * Statically set vars for the controller
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public static function set($key, $value=null)
	{
		if(self::$public_vars === null)
			self::$public_vars = new Hash;
		if($value === null)
			return self::$public_vars->$key;
		self::$public_vars->$key = $value;
	}
	/**
	 * Set the public vars in to the controller scope
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	private function setStaticPublicVars()
	{
		if(self::$public_vars == null)
			return;
		foreach(self::$public_vars->export() as $key => $value){
			$this->$key = $value;
		}
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
	 * Flash the view with items.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function flash($key=null, $value=null)
	{
		if($key !== null && $value === null)
			return $this->flash->set('default', $key);
		if($key !== null && $value !== null)
			return $this->flash->set($key, $value);
		return $this->flash;
	}
	/**
	 * Return request params
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	protected function params($key=null, $value=null)
	{
		return ($key !== null) ? $this->pr_request->params($key, $value)
							     : $this->pr_request;
	}
}
