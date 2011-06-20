<?php
/**
 * Is the current model property an integer.
 *
 * @package html
 * @subpackage rules
 * @author Justin Palmer
 **/
class InternationalPostalCodeRule extends AlphaExtraRule
{
	/**
	 * constructor
	 *
	 * @return NameRule
	 * @author Justin Palmer
	 **/
	public function __construct($message='%s should only include lowercase and capital letters, hyphen and space.')
	{
		parent::__construct("0-9\s\-", $message);
	}
} // END class Rule
