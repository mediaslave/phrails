<?php
/**
* If the rule does not override the $message var the we will throw an exception.
* @package exceptions
*/
class ActiveRecordNoIdException extends Exception
{
	
	function __construct($model)
	{
		parent::__construct('No primary key');
	}
}