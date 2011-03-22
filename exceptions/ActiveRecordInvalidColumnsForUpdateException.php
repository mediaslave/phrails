<?php
/**
* If the rule does not override the $message var the we will throw an exception.
* @package exceptions
*/
class ActiveRecordInvalidColumnsForUpdateException extends Exception
{
	
	function __construct($model, $invalid_columns)
	{
		parent::__construct('Active Record invalid columns for update in ' . $model . ': ' . implode(',', $invalid_columns));
	}
}