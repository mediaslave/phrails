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
class UsSocialSecurityNumberRule extends PregRule
{
	/**
	 * constructor
	 *
	 * @return NameRule
	 * @author Justin Palmer
	 **/
	public function __construct($message='%s should be in the format: ###-##-####.')
	{
		parent::__construct("/^(\d{3})\-(\d{2})\-(\d{4})$/", $message);
	}
} // END class Rule
