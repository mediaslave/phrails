<?php
/**
 * Is the current model property a float.
 *
 * @package html
 * @subpackage rules
 * @author Dave Kerschner
 **/
class FloatRule extends Rule
{
	/**
	 * @see Rule::message
	 */
	public $message = '%s should be a decimal number.';
	
	/**
	 * @see Rule::run()
	 **/
	 public function run(){
		return parent::run(!is_float($this->value));
	 }
} // END class Rule
