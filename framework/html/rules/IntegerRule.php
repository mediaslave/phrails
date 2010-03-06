<?php
/**
 * base rule
 *
 * @package html
 * @subpackage rules
 * @author Justin Palmer
 **/
class IntegerRule extends Rule
{
	public $message = '%s should be an integer.';
	
	/**
	 * @see Rule::run()
	 **/
	 public function run(){
		return parent::run(!is_int($this->value));
	 }
} // END class Rule