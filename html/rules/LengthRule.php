<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
 */
/**
 * class description
 *
 * @package html
 * @subpackage rules
 * @author Justin Palmer
 */
class LengthRule extends Rule
{
	public $length;
	/**
	 * constructor
	 *
	 * @return LengthRule
	 * @author Justin Palmer
	 **/
	public function __construct($length, $message='%s should be {length} characters long.')
	{
		$this->length = $length;
		$this->message = str_replace('{length}', $length, $message);
	}

	/**
	 * @see Rule::run()
	 **/
	 public function run(){
		return parent::run(strlen($this->value) != $this->length);
	 }
} // END class Rule
