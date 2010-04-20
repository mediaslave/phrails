<?php
/**
 * Is the current model property an integer.
 *
 * @package html
 * @subpackage rules
 * @author Justin Palmer
 **/
class UsStreetRule extends AlphaExtraRule
{
	/**
	 * constructor
	 *
	 * @return NameRule
	 * @author Justin Palmer
	 **/
	public function __construct($message='%s should only include lowercase and capital letters, foreign language characters, hyphen, period, space, comma and apostrophe.')
	{
		parent::__construct("\s\,\-\.\'#:+0-9\pL", $message);
	}
} // END class Rule