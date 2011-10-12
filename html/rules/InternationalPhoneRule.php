<?php
/**
 * Is the current model property an integer.
 *
 * @package html
 * @subpackage rules
 * @author Justin Palmer
 **/
class InternationalPhoneRule extends PregRule
{
	/**
	 * constructor
	 *
	 * @return NameRule
	 * @author Justin Palmer
	 **/
	public function __construct($message='%s is not valid.')
	{
		parent::__construct("/^(\+|\+\s)?([\(0-9\)]){1,5}(\-|\s)?([\(0-9\)]){1,5}(\-|\s)?([0-9\s\-]){2,15}$/", $message);
	}
} // END class Rule
