<?php
/**
 * Is the current model property an integer.
 *
 * @package html
 * @subpackage rules
 * @author Justin Palmer
 **/
class RequiredRule extends Rule
{
	/**
	 * @see Rule::message
	 */
	public $message = '%s is required.';
	
	/**
	 * @see Rule::run()
	 **/
	 public function run(){
		return parent::run($this->value == '' || $this->value == null);
	 }
} // END class Rule