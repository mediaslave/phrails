<?php
/**
 * Is the current model property an integer.
 *
 * @package html
 * @subpackage rules
 * @author Justin Palmer
 **/
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