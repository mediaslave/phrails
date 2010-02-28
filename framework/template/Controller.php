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
	public $pr_view_type = '.html';
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
	
}