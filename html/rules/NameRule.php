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
		parent::__construct("\,\-\.\s\'\pL", $message);
	}
} // END class Rule
