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
	public function __construct($message='%s is invalid. Please select an option provided.')
	{
		parent::__construct("/Dr\.|Miss|Mr\.|Mrs\.|Ms\.|Prof\./", $message);
	}
} // END class Rule
