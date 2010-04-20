<?php
/**
 * Is the current model property an integer.
 *
 * @package html
 * @subpackage rules
 * @author Justin Palmer
 **/
class NameRule extends AlphaExtraRule
{
	/**
	 * constructor
	 *
	 * @return NameRule
	 * @author Justin Palmer
	 **/
	public function __construct($message='%s should only include lowercase and capital letters, foreign language characters, hyphen, period, space and apostrophe.')
	{
		parent::__construct("\-\.\s'\pL", $message);
	}
} // END class Rule