<?php
/**
* If the rule does not override the $message var the we will throw an exception.
* @package exceptions
*/
class UnknownActiveRecordDynamicFinderException extends Exception
{
	
	function __construct($method)
	{
		parent::__construct("The dynamic finder: `$method` could not be found");
	}
}