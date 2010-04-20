<?php
/**
 * Is the current model property an integer.
 *
 * @package html
 * @subpackage rules
 * @author Justin Palmer
 **/
class UsTaxIdRule extends PregRule
{
	/**
	 * constructor
	 *
	 * @return NameRule
	 * @author Justin Palmer
	 **/
	public function __construct($message='%s should be in the format: ##-####### OR #########.')
	{
		parent::__construct("/^(\d{2})\-?(\d{7})$/", $message);
	}
} // END class Rule