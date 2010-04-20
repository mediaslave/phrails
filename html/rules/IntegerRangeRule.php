<?php
/**
 * Is the current model property an integer.
 *
 * @package html
 * @subpackage rules
 * @author Justin Palmer
 **/
class IntegerRangeRule extends Rule
{
	public $min;
	public $max;
	/**
	 * constructor
	 *
	 * @return LengthRule
	 * @author Justin Palmer
	 **/
	public function __construct($min, $max, $message='%s should be between {min} and {max}.')
	{
		$this->length = $length;
		$message = str_replace('{min}', $min, $message);
		$this->message = str_replace('{max}', $max, $message);
	}
	
	/**
	 * @see Rule::run()
	 **/
	 public function run(){
		return parent::run(!($this->value >= $this->min && $this->value <= $this->max));
	 }
} // END class Rule