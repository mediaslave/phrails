<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package html
 * @subpackage rules
 */
/**
 * class description
 *
 * @package html
 * @subpackage rules
 */
class IntegerRule extends Rule
{
	/**
	 * @see Rule::message
	 */
	public $message = '%s should be a number.';

	/**
	 * @see Rule::run()
	 **/
	 public function run(){
		return parent::run(!is_numeric($this->value));
	 }
} // END class Rule
