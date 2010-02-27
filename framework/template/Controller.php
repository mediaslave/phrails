<?php
/**
* 
*/
class Controller
{
	public $pr_layout = 'application';
	public $pr_controller;
	public $pr_action;
	public $pr_view_type = '.html';
	public $pr_get;
	public function __construct()
	{
		$this->pr_controller = get_class($this);
		$this->pr_get = new Hash($_GET);
		$this->pr_post = new Hash($_POST);
		$this->pr_session = new Hash($_SESSION);
		$this->pr_files = new Hash($_FILES);
		$this->pr_server = new Hash($_SERVER);
	}
	/**
	 * No controller action.
	 */
	public function prNoRoute(){
		$this->pr_action = 'prNoRoute';
	}
	/**
	 * No controller action.
	 */
	public function prNoController(){
		$this->pr_action = 'prNoController';
	}
	/**
	 * No controller action.
	 */
	public function prNoAction(){
		$this->pr_action = 'prNoAction';
	}
	
}