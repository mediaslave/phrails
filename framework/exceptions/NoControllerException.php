<?php
/**
* No controller for the specified route.
* @package exceptions
*/
class NoControllerException extends Exception
{
	
	function __construct()
	{
		parent::__construct();
	}
}
