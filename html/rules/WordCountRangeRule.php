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
class WordCountRangeRule extends Rule
{
	/**
	 * Min and Max word count
	 *
	 * @var integer
	 */
	private $min, $max;
	/**
	 * constructor
	 *
	 * @param integer $min The minimum number of words
	 * @param integer $max The maximum number of words
	 * @param string $message The message for an invalid rule run
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
	 * @return boolean
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
