<?php
/**
* If the rule does not override the $message var the we will throw an exception.
* @package exceptions
*/
class FailedActiveRecordCreateUpdateException extends Exception
{
	
	function __construct()
	{
		parent::__construct();
	}
}