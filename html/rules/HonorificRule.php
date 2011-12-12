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
 * @author Justin Palmer
 */
class HonorificRule extends PregRule
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
