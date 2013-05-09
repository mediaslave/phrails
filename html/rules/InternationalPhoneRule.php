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
		parent::__construct("/^((\+)[0-9]{1,4})?([0-9]){1,5}(\s\.\-)?([0-9]){1,5}(\s\.\-)?([0-9 \-]){2,15}\s?(ext\.|x\.|ext|x|extension)?\s?([0-9]){1,6}?$/", $message);
	}
} // END class Rule
