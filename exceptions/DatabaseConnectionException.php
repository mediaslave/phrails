<?php
/**
* If the rule does not override the $message var the we will throw an exception.
* @package exceptions
*/
class DatabaseConnectionException extends Exception
{
	
	function __construct($message)
	{
		parent::__construct($message);
	}
}