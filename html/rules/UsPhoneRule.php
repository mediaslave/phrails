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
class UsPhoneRule extends PregRule
{
	/**
	 * constructor
	 *
	 * @return NameRule
	 * @author Justin Palmer
	 **/
	public function __construct($message='%s should be in the format: ###-###-####')
	{
		parent::__construct("/^([0-9]){3}(\s|-)([0-9]){3}(-|\s)([0-9]){4}$/", $message);
	}
} // END class Rule
