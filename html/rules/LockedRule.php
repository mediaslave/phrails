<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
 * @package html
 * @subpackage rules
 */
/**
 * class description
 *
 * @package html
 * @subpackage rules
 * @author Justin Palmer
 */
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
