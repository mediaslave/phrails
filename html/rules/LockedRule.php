<?php
/**
 * If the model property is locked then it can not be written to.
 *
 * @package html
 * @subpackage rules
 * @author Justin Palmer
 **/
class LockedRule extends Rule
{
	/**
	 * @see Rule::message
	 */
	public $message = '%s is locked.  It can not be modified.';
	/**
	 * @see Rule::run()
	 **/
	 public function run(){
		return parent::run(true);
	 }
} // END class Rule