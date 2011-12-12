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
