<?php
/**
* If the rule does not override the $message var the we will throw an exception.
* @package exceptions
*/
class UnknownDatabaseTypeException extends Exception
{
	
	function __construct($type)
	{
		parent::__construct("Database type: `$type` is unknown.");
	}
}