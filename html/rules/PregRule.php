<?php
/**
 * Is the current model property an integer.
 *
 * @package html
 * @subpackage rules
 * @author Justin Palmer
 **/
class PregRule extends Rule
{
	public $preg;
	/**
	 * Constructor
	 *
	 * @return PregRule
	 * @author Justin Palmer
	 **/
	public function __construct($preg=null, $message=null)
	{
		$this->preg = $preg;
		$this->message = $message;
		parent::__construct();
	}
	
	/**
	 * @see Rule::run()
	 **/
	 public function run(){
		return parent::run(!preg_match($this->preg, $this->value));
	 }
} // END class Rule