<?php
/**
 * base rule
 *
 * @package html
 * @subpackage rules
 * @author Justin Palmer
 **/
class LockedRule extends Rule
{
	public $message = '%s is locked.  It can not be modified.';
	/**
	 * @see Rule::run()
	 **/
	 public function run(){
		return parent::run(true);
	 }
} // END class Rule