<?php
/**
* 
*/
class Controller
{
	public $pr_layout = 'application';
	public $pr_controller;
	public function __construct()
	{
		$this->pr_controller = get_class($this);
	}
	/**
	 * No controller action.
	 */
	public function prNoController(){}
	
}