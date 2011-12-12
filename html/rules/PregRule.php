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
class PregRule extends Rule
{
	public $preg;
	/**
	 * Constructor
	 *
	 * @return PregRule
	 * @author Justin Palmer
	 **/
	public function __construct($preg=null, $message=null)
	{
		$this->preg = $preg;
		$this->message = $message;
		parent::__construct();
	}

	/**
	 * @see Rule::run()
	 **/
	 public function run(){
		return parent::run(!preg_match($this->preg, $this->value));
	 }
} // END class Rule
