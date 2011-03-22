<?php
/**
 * Is the current model property an integer.
 *
 * @package html
 * @subpackage rules
 * @author Justin Palmer
 **/
class NumericExtraRule extends PregRule
{
	/**
	 * constructor
	 *
	 * @return AlphaExtraRule
	 * @author Justin Palmer
	 **/
	public function __construct($extra='', $message='%s should only include lowercase and capital letters.')
	{
		$preg = '/^([0-9' . $extra . '])+$/';
		parent::__construct($preg, $message);
	}
} // END class Rule