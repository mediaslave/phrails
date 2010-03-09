<?php
/**
 * Is the current model property an integer.
 *
 * @package html
 * @subpackage rules
 * @author Justin Palmer
 **/
class IntegerRule extends Rule
{
	/**
	 * @see Rule::message
	 */
	public $message = '%s should be an integer.';
	
	/**
	 * @see Rule::run()
	 **/
	 public function run(){
		return parent::run(!is_numeric($this->value));
	 }
} // END class Rule