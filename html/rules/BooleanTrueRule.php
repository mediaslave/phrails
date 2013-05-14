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
class BooleanTrueRule extends Rule
{
	/**
	 * @see Rule::message
	 */
	public $message = '%s must be true.';

	/**
	 * @see Rule::run()
	 **/
	 public function run(){
		return parent::run($this->value != 1);
	 }
} // END class Rule
