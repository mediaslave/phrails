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
	 * No respond to type to respond with
	 *
	 * @author Justin Palmer
	 **/
	public function prNoViewType()
	{
		$this->pr_action = 'prNoViewType';
	}
	
}