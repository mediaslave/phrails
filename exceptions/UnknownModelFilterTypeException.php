<?php
/**
* If the rule does not override the $message var the we will throw an exception.
* @package exceptions
*/
class UnknownModelFilterTypeException extends Exception
{
	
	function __construct($type)
	{
		parent::__construct('Unknown Model Filter Type: ' . $type);
	}
}