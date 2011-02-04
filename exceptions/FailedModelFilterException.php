<?php
/**
* If the rule does not override the $message var the we will throw an exception.
* @package exceptions
*/
class FailedModelFilterException extends Exception
{
	
	function __construct($class_name, $filter_type, $filter)
	{
		if(is_array($filter)){
			$filter = implode('->', $filter);
		}
		parent::__construct("In object $class_name::$filter_type running $filter failed.");
	}
}