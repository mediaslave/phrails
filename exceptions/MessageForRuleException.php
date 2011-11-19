<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
 * @package exceptions
 */

/**
* @package exceptions
* @author Justin Palmer
*/
class MessageForRuleException extends Exception
{

	function __construct($class)
	{
		parent::__construct("Every rule must override: '\$message' in $class.");
	}
}
