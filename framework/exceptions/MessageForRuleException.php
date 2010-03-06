<?php
/**
* 
*/
class MessageForRuleException extends Exception
{
	
	function __construct($class)
	{
		parent::__construct("Every rule must override: '\$message' in $class.");
	}
}
