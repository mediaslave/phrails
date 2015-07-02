<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package html
 * @subpackage rules
 */
/**
 * Is the current model property an integer.
 *
 * @package html
 * @subpackage rules
 **/
class EmailRule extends PregRule
{
	/**
	 * constructor
	 *
	 * @return NameRule
	 * @author Justin Palmer
	 **/
	public function __construct($message='%s is an invalid email address.')
	{
		parent::__construct("/^[0-9a-zA-Z._%?=^+\-!#$'/*{|}~`&]+@[0-9a-zA-Z.\-]+\.[0-9a-zA-Z.]{2,512}$/", $message);
	}
} // END class Rule
