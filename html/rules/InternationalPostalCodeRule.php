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
class InternationalPostalCodeRule extends AlphaExtraRule
{
	/**
	 * constructor
	 *
	 * @return NameRule
	 * @author Justin Palmer
	 **/
	public function __construct($message='%s should only include lowercase and capital letters, foreign language characters, hyphen and space.')
	{
		parent::__construct("0-9\s\-", $message);
	}
} // END class Rule
