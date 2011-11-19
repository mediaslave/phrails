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
class LengthRangeRule extends Rule
{
	public $min;
	public $max;
	/**
	 * constructor
	 *
	 * @return LengthRule
	 * @author Justin Palmer
	 **/
	public function __construct($min, $max, $message='%s should be between {min} and {max} characters long.')
	{
		$this->min = $min;
		$this->max = $max;
		$message = str_replace('{min}', $min, $message);
		$this->message = str_replace('{max}', $max, $message);
		parent::__construct();
	}

	/**
	 * @see Rule::run()
	 **/
	 public function run(){
		return parent::run(!(strlen($this->value) >= $this->min && strlen($this->value) <= $this->max));
	 }
} // END class Rule
