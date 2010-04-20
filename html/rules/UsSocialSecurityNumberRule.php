<?php
/**
 * Is the current model property an integer.
 *
 * @package html
 * @subpackage rules
 * @author Justin Palmer
 **/
class UsSocialSecurityNumberRule extends PregRule
{
	/**
	 * constructor
	 *
	 * @return NameRule
	 * @author Justin Palmer
	 **/
	public function __construct($message='%s should be in the format: ###-###-####.')
	{
		parent::__construct("/^(\d{3})\-(\d{3})\-(\d{4})$/", $message);
	}
} // END class Rule