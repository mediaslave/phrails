<?php
/**
 * Is the current model property an integer.
 *
 * @package html
 * @subpackage rules
 * @author Justin Palmer
 **/
class WordCountRangeRule extends Rule
{
	private $min, $max;
	/**
	 * constructor
	 *
	 * @return NameRule
	 * @author Justin Palmer
	 **/
	public function __construct($min=0, $max=null, $message='')
	{
		$this->min = $min;
		$this->max = $max;
		$this->message = '%s should have a minimum word count of ' . $min;
		if($max !== null)
			$this->message .= ' and a maximum word count of ' . $max;
		parent::__construct($message);
	}
	/**
	 * Run the rule
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function run()
	{
		$count = str_word_count($this->value);
		if($this->max === null){
			return parent::run($count < $this->min);
		}else{
			return parent::run($count < $this->min || $count > $this->max);
		}
	}
} // END class Rule