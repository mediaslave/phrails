<?php
/**
 * * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
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
class BooleanRule extends Rule
{
	/**
	 * @see Rule::message
	 */
	public $message = '%s should be true or false.';

	/**
	 * @see Rule::run()
	 **/
	 public function run(){
		return parent::run($this->value != 0 && $this->value != 1);
	 }
} // END class Rule
