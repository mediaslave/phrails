<?php
/**
 * Run a function in the model to validate a field
 *
 * @package html
 * @subpackage rules
 * @author Dave Kerschner
 **/
class ModelRule extends Rule
{
	
	/**
	 * The method in the Model to call to validate the field
	 *
	 * @author Dave Kerschner (dkerschner@cetusa.org)
	 * @access private
	 */
	private $method;

	
	public function __construct($method, $message) {
		$this->method = $method;
		$this->message = $message;
		parent::__construct();
	}
	
	/**
	 * @see Rule::run()
	 **/
	 public function run(){
		 $method = $this->method;
		 return $this->model->$method();
	 }
} // END class Rule