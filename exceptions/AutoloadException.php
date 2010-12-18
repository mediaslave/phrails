<?php
/**
* If the rule does not override the $message var the we will throw an exception.
* @package exceptions
*/
class AutoloadException extends Exception
{
	
	function __construct($class_name)
	{
		parent::__construct('The object `' . $class_name . '` could not be loaded with the autoload.');
	}
}