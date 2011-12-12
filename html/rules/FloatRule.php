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
 * @author Justin Palmer
 */
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
