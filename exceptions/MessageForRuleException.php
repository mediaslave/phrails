<?php
/**
* If the rule does not override the $message var the we will throw an exception.
* @package exceptions
*/
class MessageForRuleException extends Exception
{
	
	function __construct($class)
	{
		parent::__construct("Every rule must override: '\$message' in $class.");
	}
}
