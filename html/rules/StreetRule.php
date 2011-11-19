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
class StreetRule extends AlphaExtraRule
{
	/**
	 * constructor
	 *
	 * @return NameRule
	 * @author Justin Palmer
	 **/
	public function __construct($message='%s should only include lowercase and capital letters, foreign language characters, hyphen, period, space, comma and apostrophe.')
	{
		parent::__construct("\s\,\-\.\'\(\)#:+0-9\pL", $message);
	}
} // END class Rule
