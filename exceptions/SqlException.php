<?php
/**
* When a generic sql error is thrown.
* @package exceptions
*/
class SqlException extends Exception
{
	function __construct($message, $code)
	{
		parent::__construct($message, $code);
	}
}