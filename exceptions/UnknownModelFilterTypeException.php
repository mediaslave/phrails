<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
 * @package exceptions
 */

/**
* @package exceptions
* @author Justin Palmer
*/
class UnknownModelFilterTypeException extends Exception
{

	function __construct($type)
	{
		parent::__construct('Unknown Model Filter Type: ' . $type);
	}
}
